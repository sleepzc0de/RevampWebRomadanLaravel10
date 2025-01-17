<?php

namespace App\Services;

class CaptchaService
{
    private $operators = ['+', '-', '*'];
    private $difficulties = [
        'easy' => ['min' => 1, 'max' => 9],
        'medium' => ['min' => 10, 'max' => 20],
        'hard' => ['min' => 20, 'max' => 50]
    ];

    public function createCaptcha()
    {
        // Randomly select difficulty and operator
        $difficulty = array_rand($this->difficulties);
        $operator = $this->operators[array_rand($this->operators)];

        // Generate numbers based on difficulty
        $range = $this->difficulties[$difficulty];
        $num1 = rand($range['min'], $range['max']);
        $num2 = rand($range['min'], $range['max']);

        // Ensure subtraction doesn't result in negative numbers
        if ($operator === '-' && $num1 < $num2) {
            // Swap numbers
            list($num1, $num2) = [$num2, $num1];
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
        }

        // Store answer in session
        session(['captcha_string' => (string)$answer]);

        // Add some "noise" text to make it harder for bots
        $noiseWords = ['hitung', 'berapakah', 'hasil dari'];
        $noise = $noiseWords[array_rand($noiseWords)];

        // Return the formatted question
        return "$noise $question = ?";
    }

    public function validateCaptcha($input)
    {
        $captcha = session('captcha_string');
        return $captcha === $input;
    }
}
