<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Stayly - Tell us about your place</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?= DEFAULT_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= DEFAULT_URL ?>assets/css/toast.css">
    <script>
        window.currentStep = <?= $step ?>;
    </script>
    <link rel="stylesheet" href="<?= DEFAULT_URL ?>assets/css/wizard.css">
    <script src="<?= DEFAULT_URL ?>assets/js/wizard.js"></script>
</head>

<body>
    <?php if ($step != 7): ?>

        <nav>
            <a href="<?= DEFAULT_URL ?>public" class="logo">
                <svg width="189" height="68" viewBox="0 0 189 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_459_478)">
                        <rect width="188.929" height="67.5714" fill="white" />
                        <path d="M24.5385 0C11.0423 0 0 11.0571 0 24.5714C0 45.4571 24.5385 67.5714 24.5385 67.5714C24.5385 67.5714 49.077 45.4571 49.077 24.5714C49.077 11.0571 38.0346 0 24.5385 0Z" fill="#FF5B8C" />
                        <path d="M17.1769 27.2429V19.8714H31.9V27.2429" stroke="white" stroke-width="3" stroke-linecap="round" />
                        <path d="M12.2692 27.2429L24.5385 17.4143L36.8077 27.2429" stroke="white" stroke-width="3" stroke-linecap="round" />
                        <path d="M98.503 41.5278C97.031 41.5278 95.7083 41.2718 94.535 40.7598C93.383 40.2265 92.4763 39.5012 91.815 38.5838C91.1536 37.6452 90.8123 36.5678 90.791 35.3518H93.895C94.0016 36.3972 94.4283 37.2825 95.175 38.0078C95.943 38.7118 97.0523 39.0638 98.503 39.0638C99.8896 39.0638 100.978 38.7225 101.767 38.0398C102.578 37.3358 102.983 36.4398 102.983 35.3518C102.983 34.4985 102.748 33.8052 102.279 33.2718C101.81 32.7385 101.223 32.3332 100.519 32.0558C99.815 31.7785 98.8656 31.4798 97.671 31.1598C96.199 30.7758 95.015 30.3918 94.119 30.0078C93.2443 29.6238 92.487 29.0265 91.847 28.2158C91.2283 27.3838 90.919 26.2745 90.919 24.8878C90.919 23.6718 91.2283 22.5945 91.847 21.6558C92.4656 20.7172 93.3296 19.9918 94.439 19.4798C95.5696 18.9678 96.8603 18.7118 98.311 18.7118C100.402 18.7118 102.108 19.2345 103.431 20.2798C104.775 21.3252 105.532 22.7118 105.703 24.4398H102.503C102.396 23.5865 101.948 22.8398 101.159 22.1998C100.37 21.5385 99.3243 21.2078 98.023 21.2078C96.807 21.2078 95.815 21.5278 95.047 22.1678C94.279 22.7865 93.895 23.6612 93.895 24.7918C93.895 25.6025 94.119 26.2638 94.567 26.7758C95.0363 27.2878 95.6016 27.6825 96.263 27.9598C96.9456 28.2158 97.895 28.5145 99.111 28.8558C100.583 29.2612 101.767 29.6665 102.663 30.0718C103.559 30.4558 104.327 31.0638 104.967 31.8958C105.607 32.7065 105.927 33.8158 105.927 35.2238C105.927 36.3118 105.639 37.3358 105.063 38.2958C104.487 39.2558 103.634 40.0345 102.503 40.6318C101.372 41.2292 100.039 41.5278 98.503 41.5278ZM113.764 26.1678V36.5038C113.764 37.3572 113.946 37.9652 114.308 38.3278C114.671 38.6692 115.3 38.8398 116.196 38.8398H118.34V41.3038H115.716C114.095 41.3038 112.879 40.9305 112.068 40.1838C111.258 39.4372 110.852 38.2105 110.852 36.5038V26.1678H108.58V23.7678H110.852V19.3518H113.764V23.7678H118.34V26.1678H113.764ZM120.78 32.4718C120.78 30.6798 121.143 29.1118 121.868 27.7678C122.594 26.4025 123.586 25.3465 124.844 24.5998C126.124 23.8532 127.543 23.4798 129.1 23.4798C130.636 23.4798 131.97 23.8105 133.1 24.4718C134.231 25.1332 135.074 25.9652 135.628 26.9678V23.7678H138.572V41.3038H135.628V38.0398C135.052 39.0638 134.188 39.9172 133.036 40.5998C131.906 41.2612 130.583 41.5918 129.068 41.5918C127.511 41.5918 126.103 41.2078 124.844 40.4398C123.586 39.6718 122.594 38.5945 121.868 37.2078C121.143 35.8212 120.78 34.2425 120.78 32.4718ZM135.628 32.5038C135.628 31.1812 135.362 30.0292 134.828 29.0478C134.295 28.0665 133.57 27.3198 132.652 26.8078C131.756 26.2745 130.764 26.0078 129.676 26.0078C128.588 26.0078 127.596 26.2638 126.7 26.7758C125.804 27.2878 125.09 28.0345 124.556 29.0158C124.023 29.9972 123.756 31.1492 123.756 32.4718C123.756 33.8158 124.023 34.9892 124.556 35.9918C125.09 36.9732 125.804 37.7305 126.7 38.2638C127.596 38.7758 128.588 39.0318 129.676 39.0318C130.764 39.0318 131.756 38.7758 132.652 38.2638C133.57 37.7305 134.295 36.9732 134.828 35.9918C135.362 34.9892 135.628 33.8265 135.628 32.5038ZM158.597 23.7678L148.037 49.5598H145.029L148.485 41.1118L141.413 23.7678H144.645L150.149 37.9758L155.589 23.7678H158.597ZM164.437 17.6238V41.3038H161.525V17.6238H164.437ZM184.504 23.7678L173.944 49.5598H170.936L174.392 41.1118L167.32 23.7678H170.552L176.056 37.9758L181.496 23.7678H184.504Z" fill="#FF5B8C" />
                    </g>
                    <defs>
                        <clipPath id="clip0_459_478">
                            <rect width="188.929" height="67.5714" fill="white" />
                        </clipPath>
                    </defs>
                </svg>
            </a>

            <div class="nav-actions">
                <a href="<?= DEFAULT_URL ?>/public/Home/showHost" class="btn-save">Save &amp; exit</a>
                <a href="<?= DEFAULT_URL ?>/public/Home/showHost" class="btn-close" aria-label="Close">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M2 2l12 12M14 2L2 14" stroke="#444" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </a>
            </div>
        </nav>


        <div class="progress-bar" role="tablist" aria-label="Listing steps">
            <div class="step active" role="tab" aria-selected="true">
                <div class="step-circle">
                    <div class="dot"></div>
                </div>
                <span class="step-label">Basic info</span>
            </div>
            <div class="step-sep"></div>
            <div class="step" role="tab" aria-selected="false">
                <div class="step-circle">
                    <div class="dot"></div>
                </div>
                <span class="step-label">Location</span>
            </div>
            <div class="step-sep"></div>
            <div class="step" role="tab" aria-selected="false">
                <div class="step-circle">
                    <div class="dot"></div>
                </div>
                <span class="step-label">Photos</span>
            </div>
            <div class="step-sep"></div>
            <div class="step" role="tab" aria-selected="false">
                <div class="step-circle">
                    <div class="dot"></div>
                </div>
                <span class="step-label">Details</span>
            </div>
            <div class="step-sep"></div>
            <div class="step" role="tab" aria-selected="false">
                <div class="step-circle">
                    <div class="dot"></div>
                </div>
                <span class="step-label">Pricing</span>
            </div>
            <div class="step-sep"></div>
            <div class="step" role="tab" aria-selected="false">
                <div class="step-circle">
                    <div class="dot"></div>
                </div>
                <span class="step-label">Review</span>
            </div>
        </div>
    <?php endif; ?>

    <?php switch ($step) {
        case 1: ?>
            <main class="step step-1">
                <form method="POST" id="propertyForm">
                    <h1 class="heading">Tell us about your place</h1>
                    <p class="subheading">This information helps guests understand what kind of place you're offering.</p>

                    <div class="field" style="margin-bottom: 28px;">
                        <label for="title">Listing title</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            placeholder="e.g. Cozy apartment in the heart of Madrid"
                            maxlength="100"
                            value="<?= isset($_SESSION['property']['title']) ? $_SESSION['property']['title'] : '' ?>">
                    </div>

                    <div class="cards" role="group" aria-label="Property type">
                        <label class="option">
                            <input type="radio" name="prop-type" id="apart" value="Apartment">

                            <div class="card option-card" aria-pressed="false">
                                <div class="card-icon">
                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 16.6667H20.0167" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M20 23.3333H20.0167" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M20 10H20.0167" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M26.6665 16.6667H26.6832" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M26.6665 23.3333H26.6832" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M26.6665 10H26.6832" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.3335 16.6667H13.3502" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.3335 23.3333H13.3502" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.3335 10H13.3502" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M15 36.6667V31.6667C15 31.2246 15.1756 30.8007 15.4882 30.4882C15.8007 30.1756 16.2246 30 16.6667 30H23.3333C23.7754 30 24.1993 30.1756 24.5118 30.4882C24.8244 30.8007 25 31.2246 25 31.6667V36.6667" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M29.9998 3.33334H9.99984C8.15889 3.33334 6.6665 4.82573 6.6665 6.66668V33.3333C6.6665 35.1743 8.15889 36.6667 9.99984 36.6667H29.9998C31.8408 36.6667 33.3332 35.1743 33.3332 33.3333V6.66668C33.3332 4.82573 31.8408 3.33334 29.9998 3.33334Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <span class="card-label">Apartment</span>
                            </div>
                        </label>

                        <label class="option">
                            <input type="radio" name="prop-type" id="house" value="House">

                            <div class="card option-card" aria-pressed="false">
                                <div class="card-icon">
                                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M30 42V26C30 25.4696 29.7893 24.9609 29.4142 24.5858C29.0391 24.2107 28.5304 24 28 24H20C19.4696 24 18.9609 24.2107 18.5858 24.5858C18.2107 24.9609 18 25.4696 18 26V42"
                                            stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M6 20C5.99986 19.4181 6.12667 18.8433 6.37158 18.3154C6.61648 17.7876 6.9736 17.3196 7.418 16.944L21.418 4.94401C22.14 4.33383 23.0547 3.99905 24 3.99905C24.9453 3.99905 25.86 4.33383 26.582 4.94401L40.582 16.944C41.0264 17.3196 41.3835 17.7876 41.6284 18.3154C41.8733 18.8433 42.0001 19.4181 42 20V38C42 39.0609 41.5786 40.0783 40.8284 40.8284C40.0783 41.5786 39.0609 42 38 42H10C8.93913 42 7.92172 41.5786 7.17157 40.8284C6.42143 40.0783 6 39.0609 6 38V20Z"
                                            stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <span class="card-label">House</span>
                            </div>
                        </label>

                        <label class="option">
                            <input type="radio" name="prop-type" id="room" value="Room">

                            <div class="card option-card" aria-pressed="false">
                                <div class="card-icon">
                                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M22 40H4" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M22 9.124V41.438C22.0001 41.7418 22.0694 42.0416 22.2026 42.3146C22.3358 42.5876 22.5295 42.8267 22.769 43.0137C23.0084 43.2007 23.2873 43.3307 23.5844 43.3938C23.8816 43.4569 24.1892 43.4515 24.484 43.378L38 40V11.124C37.9999 10.232 37.7016 9.36567 37.1526 8.66266C36.6036 7.95965 35.8354 7.4603 34.97 7.244L26.97 5.244C26.3805 5.09665 25.7652 5.08553 25.1707 5.21151C24.5763 5.33748 24.0184 5.59722 23.5393 5.97102C23.0603 6.34483 22.6727 6.82286 22.4059 7.36883C22.1392 7.9148 22.0004 8.51636 22 9.124Z"
                                            stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M22 8H16C14.9391 8 13.9217 8.42143 13.1716 9.17157C12.4214 9.92172 12 10.9391 12 12V40"
                                            stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M28 24H28.02" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M44 40H38" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <span class="card-label">Room</span>
                            </div>
                        </label>

                    </div>
                </form>
            </main>
        <?php
            break;

        case 2: ?>
            <main class="step step-2">
                <form method="POST" id="propertyForm">
                    <h1 class="heading">Where is your place located?</h1>
                    <p class="subheading">Your address is only shared with guests after they book.</p>

                    <div class="form">

                        <div class="field">
                            <label for="country">Select a country</label>
                            <div class="select-wrapper">
                                <select id="country" name="country">
                                    <option value="" <?= !isset($_SESSION['property']['country']) ? 'selected' : '' ?> disabled>Country</option>
                                    <option
                                        value="us"

                                        <?php if (isset($_SESSION['property']) && isset($_SESSION['property']['country']) && $_SESSION['property']['country'] === 'us'): ?>
                                        <?= 'selected' ?>
                                        <?php endif ?>>
                                        United States
                                    </option>
                                    <option
                                        value="uk"
                                        <?php if (isset($_SESSION['property'])  && isset($_SESSION['property']['country']) && $_SESSION['property']['country'] === 'uk'): ?>
                                        <?= 'selected' ?>
                                        <?php endif ?>>
                                        United Kingdom
                                    </option>
                                    <option

                                        value="fr"

                                        <?php if (isset($_SESSION['property']) && isset($_SESSION['property']['country']) && $_SESSION['property']['country'] === 'fr'): ?>
                                        <?= 'selected' ?>
                                        <?php endif ?>>
                                        France
                                    </option>

                                    <option
                                        value="de"

                                        <?php if (isset($_SESSION['property']) && isset($_SESSION['property']['country']) && $_SESSION['property']['country'] === 'de'): ?>
                                        <?= 'selected' ?>
                                        <?php endif ?>>
                                        Germany
                                    </option>
                                    <option value="es" <?php if (isset($_SESSION['property']) && isset($_SESSION['property']['country']) && $_SESSION['property']['country'] === 'es'): ?>
                                        <?= 'selected' ?>
                                        <?php endif ?>>Spain</option>
                                    <option value="it" <?php if (isset($_SESSION['property']) && isset($_SESSION['property']['country']) && $_SESSION['property']['country'] === 'it'): ?>
                                        <?= 'selected' ?>
                                        <?php endif ?>>Italy</option>
                                    <option value="jp" <?php if (isset($_SESSION['property']) && isset($_SESSION['property']['country']) && $_SESSION['property']['country'] === 'jp'): ?>
                                        <?= 'selected' ?>
                                        <?php endif ?>>Japan</option>
                                    <option value="au" <?php if (isset($_SESSION['property']) && isset($_SESSION['property']['country']) && $_SESSION['property']['country'] === 'au'): ?>
                                        <?= 'selected' ?>
                                        <?php endif ?>>Australia</option>
                                    <option value="ca" <?php if (isset($_SESSION['property']) && isset($_SESSION['property']['country']) && $_SESSION['property']['country'] === 'ca'): ?>
                                        <?= 'selected' ?>
                                        <?php endif ?>>Canada</option>
                                    <option value="br" <?php if (isset($_SESSION['property']) && isset($_SESSION['property']['country']) && $_SESSION['property']['country'] === 'br'): ?>
                                        <?= 'selected' ?>
                                        <?php endif ?>>Brazil</option>
                                </select>
                            </div>
                        </div>

                        <div class="field">
                            <label for="city">City</label>
                            <input id="city" type="text" name="city" placeholder="Madrid" autocomplete="address-level2" value="<?= isset($_SESSION['property']['city']) ? $_SESSION['property']['city'] : '' ?>" />
                        </div>

                        <div class="field">
                            <label for="address">Address</label>
                            <input id="address" type="text" name="address" placeholder="Calle Gran Vía 45" autocomplete="street-address" value="<?= isset($_SESSION['property']['address']) ? $_SESSION['property']['address'] : '' ?>" />
                        </div>

                        <div class="field">
                            <label for="postal">Postal Code</label>
                            <input id="postal" type="text" name="postal" placeholder="28013" autocomplete="postal-code" value="<?= isset($_SESSION['property']['postal']) ? $_SESSION['property']['postal'] : '' ?>" />
                        </div>

                    </div>
                </form>
            </main>
        <?php
            break;

        case 3: ?>
            <main class="step step-3">
                <form method="POST" id="propertyForm" enctype="multipart/form-data">
                    <h1 class="heading">Add photos of your place</h1>
                    <p class="subheading">Great photos help guests imagine staying at your place.</p>

                    <div class="upload-area" id="uploadArea">

                        <input type="file" id="fileInput" name="photos[]" multiple accept="image/*" hidden>
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
                            <path d="M4 28l8-8 5 5 7-9 8 12H4z" stroke="#888" stroke-width="1.6" stroke-linejoin="round" fill="none" />
                            <circle cx="11" cy="13" r="3" stroke="#888" stroke-width="1.6" fill="none" />
                            <rect x="2" y="2" width="32" height="32" rx="4" stroke="#888" stroke-width="1.6" fill="none" />
                        </svg>
                        <p class="upload-text">Drag and drop your photos here<br>or <span class="upload-link">click to upload</span></p>
                    </div>

                    <p class="upload-hint">Upload at least 5 photos. The first one will be the cover.</p>

                    <div class="photo-grid" id="photoGrid"></div>
                </form>
            </main>

        <?php
            break;

        case 4: ?>
            <main class="step step-4">
                <form method="POST" id="propertyForm">
                    <h1 class="heading">Add details about your place</h1>
                    <p class="subheading">Let guests know what makes your place special.</p>

                    <div class="details-form">

                        <h2 class="section-title">Property Description</h2>
                        <textarea name="description" id="description" class="description-area"
                            placeholder="Describe your place, the neighborhood, and what guests can expect."
                            maxlength="2000"></textarea>
                        <p class="char-hint" id="charHint">Minimum 100 characters recommended.</p>

                        <h2 class="section-title" style="margin-top:36px;">Capacity</h2>
                        <p class="subheading" style="margin-bottom:20px;">Tell guests how many people your place can accommodate.</p>

                        <div class="capacity-grid">

                            <div class="capacity-item">
                                <label for="max_guests">Guests</label>
                                <input type="number" id="max_guests" name="max_guests" min="1" max="20" placeholder="e.g. 4">
                            </div>

                            <div class="capacity-item">
                                <label for="rooms">Bedrooms</label>
                                <input type="number" id="rooms" name="rooms" min="0" max="20" placeholder="e.g. 2">
                            </div>

                            <div class="capacity-item">
                                <label for="bathrooms">Bathrooms</label>
                                <input type="number" id="bathrooms" name="bathrooms" min="0" max="20" step="0.5" placeholder="e.g. 1.5">
                            </div>

                        </div>

                        <h2 class="section-title" style="margin-top:36px;">Amenities</h2>
                        <p class="subheading" style="margin-bottom:20px;">Select all that apply.</p>

                        <div class="amenities-grid">
                            <label class="amenity">
                                <input type="checkbox" name="amenities[]" value="WiFi">
                                <span class="amenity-inner">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                                        <path d="M5 12.55a11 11 0 0 1 14 0" />
                                        <path d="M1.42 9a16 16 0 0 1 21.16 0" />
                                        <path d="M8.53 16.11a6 6 0 0 1 6.95 0" />
                                        <circle cx="12" cy="20" r="1" fill="currentColor" stroke="none" />
                                    </svg>
                                    <span>Wi-Fi</span>
                                </span>
                            </label>
                            <label class="amenity">
                                <input type="checkbox" name="amenities[]" value="Kitchen">
                                <span class="amenity-inner">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                                        <path d="M3 3h18v4H3zM3 7v14h18V7" />
                                        <line x1="9" y1="7" x2="9" y2="21" />
                                    </svg>
                                    <span>Kitchen</span>
                                </span>
                            </label>
                            <label class="amenity">
                                <input type="checkbox" name="amenities[]" value="parking">
                                <span class="amenity-inner">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                                        <rect x="2" y="4" width="20" height="16" rx="2" />
                                        <path d="M9 8h4a2 2 0 0 1 0 4H9v4" />
                                        <line x1="9" y1="10" x2="9" y2="16" />
                                    </svg>
                                    <span>Parking</span>
                                </span>
                            </label>
                            <label class="amenity">
                                <input type="checkbox" name="amenities[]" value="Air conditioning">
                                <span class="amenity-inner">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                                        <path d="M12 2v20M2 12h20M4.93 4.93l14.14 14.14M19.07 4.93 4.93 19.07" />
                                    </svg>
                                    <span>Air Conditioning</span>
                                </span>
                            </label>
                            <label class="amenity">
                                <input type="checkbox" name="amenities[]" value="TV">
                                <span class="amenity-inner">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                                        <rect x="2" y="4" width="20" height="14" rx="2" />
                                        <line x1="8" y1="21" x2="16" y2="21" />
                                        <line x1="12" y1="18" x2="12" y2="21" />
                                    </svg>
                                    <span>Television</span>
                                </span>
                            </label>
                            <label class="amenity">
                                <input type="checkbox" name="amenities[]" value="Washing machine">
                                <span class="amenity-inner">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                                        <rect x="2" y="2" width="20" height="20" rx="2" />
                                        <circle cx="12" cy="13" r="4" />
                                        <circle cx="8" cy="6" r="1" fill="currentColor" stroke="none" />
                                    </svg>
                                    <span>Washing machine</span>
                                </span>
                            </label>
                            <label class="amenity">
                                <input type="checkbox" name="amenities[]" value="Elevator">
                                <span class="amenity-inner">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                                        <rect x="4" y="2" width="16" height="20" rx="2" />
                                        <line x1="12" y1="2" x2="12" y2="22" />
                                        <polyline points="8 8 4 12 8 16" />
                                        <polyline points="16 8 20 12 16 16" />
                                    </svg>
                                    <span>Elevator</span>
                                </span>
                            </label>
                            <label class="amenity">
                                <input type="checkbox" name="amenities[]" value="parking2">
                                <span class="amenity-inner">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M9 8h4a2 2 0 0 1 0 4H9v4" />
                                        <line x1="9" y1="10" x2="9" y2="16" />
                                    </svg>
                                    <span>Parking</span>
                                </span>
                            </label>
                            <label class="amenity">
                                <input type="checkbox" name="amenities[]" value="Workspace">
                                <span class="amenity-inner">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                                        <rect x="2" y="6" width="20" height="12" rx="2" />
                                        <line x1="6" y1="18" x2="6" y2="21" />
                                        <line x1="18" y1="18" x2="18" y2="21" />
                                        <line x1="2" y1="10" x2="22" y2="10" />
                                    </svg>
                                    <span>Workspace</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </form>
            </main>
        <?php
            break;

        case 5: ?>
            <main class="step step-5">
                <form method="POST" id="propertyForm">
                    <h1 class="heading">Set your price</h1>
                    <p class="subheading">You can change your price at any time.</p>

                    <div class="pricing-form">
                        <h2 class="section-title">Price per night</h2>

                        <div class="price-row">
                            <input type="number" name="price" id="priceInput" class="price-input" value="120" min="1" max="99999">
                            <span class="price-unit">€ / night</span>
                        </div>

                        <p class="price-hint" id="priceHint">Guests will pay 120€ per night before fees.</p>
                    </div>
                </form>
            </main>
        <?php break;
        case 6: ?>
            <main class="step step-6">
                <form method="POST" id="propertyForm">
                    <h1 class="heading">Review your listing</h1>
                    <p class="subheading">Make sure everything looks good before publishing.</p>

                    <div class="review-cards">

                        <div class="review-card">
                            <div class="review-card-header">
                                <span class="review-card-title">Property details</span>
                                <a href="?step=1" class="edit-link">Edit</a>
                            </div>
                            <p class="review-value bold"><?= $_SESSION['property']['type'] ?></p>
                            <p class="review-value"><?= $_SESSION['property']['country'] . ',' . $_SESSION['property']['city'] ?></p>
                            <p class="review-value muted"><?= $_SESSION['property']['type'] ?></p>
                        </div>

                        <div class="review-card">
                            <div class="review-card-header">
                                <span class="review-card-title">Location</span>
                                <a href="?step=2" class="edit-link">Edit</a>
                            </div>
                            <p class="review-value bold"><?= $_SESSION['property']['country'] . ',' . $_SESSION['property']['city'] ?></p>
                            <p class="review-value"><?= $_SESSION['property']['address'] ?></p>
                            <p class="review-value"><?= $_SESSION['property']['postal'] ?></p>
                        </div>

                        <div class="review-card">
                            <div class="review-card-header">
                                <span class="review-card-title">Amenities</span>
                                <a href="?step=3" class="edit-link">Edit</a>
                            </div>
                            <div class="amenity-list">
                                <?php foreach ($_SESSION['property']['amenities'] as $amenitie): ?>
                                    <span><?= $amenitie ?></span>
                                <?php endforeach; ?>
                            </div>
                            <a href="?step=4" class="more-link"><?= count($_SESSION['property']['amenities']) ?> amenities in total</a>
                        </div>

                        <div class="review-card">
                            <div class="review-card-header">
                                <span class="review-card-title">Pricing</span>
                                <a href="?step=5" class="edit-link">Edit</a>
                            </div>
                            <p class="review-value bold"><?= $_SESSION['property']['price'] ?>€ / night</p>
                            <p class="review-value muted">Guests pay per night before fees.</p>
                        </div>

                    </div>
                </form>
            </main>
        <?php break;
        case 7: ?>
            <main class="step step-7">

                <div class="icon-wrapper">
                    <img src="<?= DEFAULT_URL ?>assets/img/circle-check(1)1.svg" alt="Pink checkmark circle icon indicating successful listing publication">
                </div>
                <h1>Your listing is live!</h1>
                <p>Your property is now visible to guests and ready to receive bookings.</p>
                <a class="btn" href="<?= DEFAULT_URL ?>public/">Go to dashboard</a>


            </main>
            <?php unset($_SESSION['property']); ?>
    <?php break;
    } ?>
    <div id="toast-container"></div>
    </main>

    <footer>
        <a href="?step=<?= max(1, $step - 1) ?>" class="btn-back">Back</a>

        <?php if ($step != 7): ?>
            <button type="submit" name="step" value="<?= $step + 1 ?>" class="btn-next" form="propertyForm">
                <?= $step != 6 ? 'next' : 'Publish' ?>
            </button>
        <?php else: ?>
            <p>You'll be redirected in <span id="countdown">5</span> seconds...</p>
        <?php endif; ?>
    </footer>

    <script src="<?= DEFAULT_URL ?>/assets/js/toast.js"></script>

    <?php if (isset($_SESSION['toast'])): ?>
        <script>
            showToast('<?= $_SESSION['toast']['type'] ?>', '<?= $_SESSION['toast']['message'] ?>');
        </script>
    <?php endif; ?>

    <?php unset($_SESSION['toast']);

    ?>
</body>

</html>