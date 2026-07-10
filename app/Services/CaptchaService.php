<?php

namespace App\Services;

use Illuminate\Support\Str;

class CaptchaService
{
    private $operators = ['+', '-', '*'];

    private $difficulties = [
        'easy' => ['min' => 1, 'max' => 9],
        'medium' => ['min' => 10, 'max' => 20],
        'hard' => ['min' => 20, 'max' => 50],
    ];

    private $maxAttempts = 3;

    private $sessionTimeout = 300; // 5 minutes in seconds

    public function createCaptcha()
    {
        // Generate a unique token for this CAPTCHA
        $token = Str::random(32);

        // Clear any expired CAPTCHA data
        $this->clearExpiredCaptcha();

        // Randomly select difficulty and operator
        $difficulty = array_rand($this->difficulties);
        $operator = $this->operators[array_rand($this->operators)];

        // Generate numbers based on difficulty
        $range = $this->difficulties[$difficulty];
        $num1 = rand($range['min'], $range['max']);
        $num2 = rand($range['min'], $range['max']);

        // Ensure subtraction doesn't result in negative numbers
        if ($operator === '-' && $num1 < $num2) {
            [$num1, $num2] = [$num2, $num1];
        }

        // Calculate answer based on operator
        switch ($operator) {
            case '+':
                $answer = $num1 + $num2;
                $question = "$num1 + $num2";
                break;
            case '-':
                $answer = $num1 - $num2;
                $question = "$num1 - $num2";
                break;
            case '*':
                // For multiplication, use smaller numbers
                $num1 = rand(2, 9);
                $num2 = rand(2, 9);
                $answer = $num1 * $num2;
                $question = "$num1 × $num2";
                break;
            default:
                throw new \InvalidArgumentException('Invalid operator');
        }

        // Add some "noise" text to make it harder for bots
        $noiseWords = ['hitung', 'berapakah', 'hasil dari'];
        $noise = $noiseWords[array_rand($noiseWords)];

        // Store CAPTCHA data in session with timestamp and attempts
        session([
            'captcha_data' => [
                'token' => $token,
                'answer' => (string) $answer,
                'created_at' => time(),
                'attempts' => 0,
            ],
        ]);

        // Return the formatted question and token
        return [
            'question' => "$noise $question = ?",
            'token' => $token,
        ];
    }

    public function validateCaptcha($input, $token)
    {
        $captchaData = session('captcha_data');

        // Check if CAPTCHA exists and hasn't expired
        if (! $captchaData ||
            $captchaData['token'] !== $token ||
            time() - $captchaData['created_at'] > $this->sessionTimeout) {
            $this->clearCaptcha();

            return false;
        }

        // Increment attempt counter
        $captchaData['attempts']++;
        session(['captcha_data' => $captchaData]);

        // Check if max attempts exceeded
        if ($captchaData['attempts'] > $this->maxAttempts) {
            $this->clearCaptcha();

            return false;
        }

        // Validate answer
        $isValid = $captchaData['answer'] === (string) $input;

        // Clear CAPTCHA data after successful validation
        if ($isValid) {
            $this->clearCaptcha();
        }

        return $isValid;
    }

    private function clearCaptcha()
    {
        session()->forget('captcha_data');
    }

    private function clearExpiredCaptcha()
    {
        $captchaData = session('captcha_data');
        if ($captchaData && time() - $captchaData['created_at'] > $this->sessionTimeout) {
            $this->clearCaptcha();
        }
    }
}
