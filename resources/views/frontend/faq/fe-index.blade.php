@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
<style>
    .faq-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .faq-item {
        margin-bottom: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-item:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .faq-question {
        padding: 1.5rem;
        background-color: #f9f9f9;
        color: #333;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        transition: all 0.3s ease;
    }

    .faq-question:after {
        content: '\f078';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        transition: all 0.3s ease;
    }

    .faq-question.active {
        background-color: #0b5dba;
        color: white;
    }

    .faq-question.active:after {
        transform: rotate(180deg);
    }

    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        padding: 0 1.5rem;
        background-color: white;
    }

    .faq-answer.show {
        max-height: 1000px;
        padding: 1.5rem;
    }

    .empty-faq {
        text-align: center;
        padding: 2rem;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .txt-judul-faq {
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 2rem;
        color: #0b5dba;
        font-weight: 700;
    }

    .faq-number {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 30px;
        height: 30px;
        background-color: #0b5dba;
        color: white;
        border-radius: 50%;
        margin-right: 12px;
        flex-shrink: 0;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .faq-question.active .faq-number {
        background-color: white;
        color: #0b5dba;
    }

    .question-content {
        display: flex;
        align-items: center;
        flex-grow: 1;
    }

    @media (max-width: 768px) {
        .faq-question {
            padding: 1.2rem;
            font-size: 0.95rem;
        }

        .txt-judul-faq {
            font-size: 2rem;
        }

        .faq-number {
            width: 25px;
            height: 25px;
            font-size: 0.85rem;
            margin-right: 8px;
        }
    }
</style>
@endsection

@section('content')
<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
    <div class="container">
        <div class="title-section-ourmenu m-b-2">
            <h5 class="txt-judul-faq m-t-2">
                Frequently Asked Questions
            </h5>
        </div>

        <div class="faq-container mt-5">
            @if ($faq->count() > 0)
                @foreach ($faq as $item)
                <div class="faq-item wow fadeInUp" data-wow-delay="{{ $loop->iteration * 0.1 }}s">
                    <div class="faq-question">
                        <div class="question-content">
                            <span class="faq-number">{{ $loop->iteration }}</span>
                            <span>{{ $item->faq_judul }}</span>
                        </div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-content">
                            {!! clean($item->faq_isi) !!}
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-faq wow fadeIn">
                    <h5 class="romadan-faq m-t-2">
                        Tidak ada Data, Harap hubungi Administrator!
                    </h5>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@section('script_fe')
<script>
    $(document).ready(function() {
        // Add animation class for better entry animations
        if (typeof WOW === 'function') {
            new WOW().init();
        }

        // FAQ toggle functionality
        $('.faq-question').click(function() {
            // Toggle active class on the question
            $(this).toggleClass('active');

            // Toggle the visibility of the answer
            const answer = $(this).next('.faq-answer');

            // Close all other open answers
            $('.faq-answer').not(answer).removeClass('show');
            $('.faq-question').not($(this)).removeClass('active');

            // Toggle the current answer
            answer.toggleClass('show');

            // Scroll to the question if it's not in view (mobile friendly)
            if($(this).hasClass('active') && $(window).width() < 768) {
                $('html, body').animate({
                    scrollTop: $(this).offset().top - 100
                }, 300);
            }
        });
    });
</script>
@endsection
