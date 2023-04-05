@if (!$cookies_accepted)
    <div
        aria-label="Politika privatnosti"
        aria-live="polite"
        class="cookies_info"
        role="dialog"
    >
        <p class = "cookies_info__text">
            Ovaj sajt koristi kolačiće. Pročitajte našu stranu o
            <a href="/zaštita-privatnosti" class="cookies_info__link">politici privatnosti</a>.
        </p>

        <form
            action = "cookiesAccepted"
            class = "cookies_info__form"
            method = "post"
        >
            {!! $csrf_field !!}
            <button
                aria-label="Zatvori"
                class="cookies_info__dismiss"
                title = "Zatvori"
                type="submit"
            >
                <span aria-hidden="true">×</span>
            </button>
        </form>
    </div>
@endif
