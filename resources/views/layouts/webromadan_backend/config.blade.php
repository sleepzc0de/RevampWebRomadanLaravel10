<!-- Demo config -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="demo_config">
    <div class="position-absolute top-50 end-100 visible">
        <button type="button" class="btn btn-yellow btn-icon translate-middle-y rounded-end-0" data-bs-toggle="offcanvas" data-bs-target="#demo_config">
            <i class="ph-gear"></i>
        </button>
    </div>

    <div class="offcanvas-header border-bottom py-0">
        <h5 class="offcanvas-title py-3">TEMA CMS ROMADAN V.1</h5>
        <button type="button" class="btn btn-light btn-sm btn-icon border-transparent rounded-pill" data-bs-dismiss="offcanvas">
            <i class="ph-x"></i>
        </button>
    </div>

    <div class="offcanvas-body">
        <div class="fw-semibold mb-2">Mode Warna</div>
        <div class="list-group mb-3">
            <label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-2">
                <div class="d-flex flex-fill my-1">
                    <div class="form-check-label d-flex me-2">
                        <i class="ph-sun ph-lg me-3"></i>
                        <div>
                            <span class="fw-bold">Light Theme</span>
                            <div class="fs-sm text-muted">Tema Terang (tema default)</div>
                        </div>
                    </div>
                    <input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="light" checked>
                </div>
            </label>

            <label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-2">
                <div class="d-flex flex-fill my-1">
                    <div class="form-check-label d-flex me-2">
                        <i class="ph-moon ph-lg me-3"></i>
                        <div>
                            <span class="fw-bold">Dark Theme</span>
                            <div class="fs-sm text-muted">Tema Gelap</div>
                        </div>
                    </div>
                    <input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="dark">
                </div>
            </label>

            <label class="list-group-item list-group-item-action form-check border-width-1 rounded mb-0">
                <div class="d-flex flex-fill my-1">
                    <div class="form-check-label d-flex me-2">
                        <i class="ph-translate ph-lg me-3"></i>
                        <div>
                            <span class="fw-bold">Tema Auto</span>
                            <div class="fs-sm text-muted">Tema menyesuaikan sistem komputer</div>
                        </div>
                    </div>
                    <input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="auto">
                </div>
            </label>
        </div>
    </div>

    <div class="border-top text-center py-2 px-3">
        <a href="#" class="btn btn-yellow fw-semibold w-100 my-1" target="_blank">
            <i class="ph-terminal-window me-2"></i>
            Tim Pengembang
        </a>
    </div>
</div>
<!-- /demo config -->