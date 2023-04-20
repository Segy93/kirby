@if ($params['reason'] === 'REQUEST')
    Na Vaš zahtev, šaljemo link za resetovanje lozinke na sajtu
    <a href="https://www.kesezakirby.rs" target="_blank">kesezakirby.rs</a>
@elseif ($params['reason'] === 'MIGRATE')
    Usled nadogradnje sistema, prilikom pokušaja prijave sa starom lozinkom,
    sajt <a href="https://www.kesezakirby.rs" target="_blank">kesezakirby.rs</a>
    automatski šalje link za postavljanje nove lozinke.
@endif

<br />
<br />

<a href="{{ $params['link'] }}">Kliknite ovde kako biste resetovali lozinku</a>
