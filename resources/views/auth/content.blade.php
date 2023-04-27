<style>
    .card {
        box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
    }
</style>
<main class="d-flex align-items-center min-vh-100 app-bg">
    <div class="container">
        <div class="row justify-content-center px-2">
            <div style="max-width: 400px">
                <div class="card rounded-0 py-2">
                    @yield('form')
                </div>
            </div>
        </div>
    </div>
</main>
