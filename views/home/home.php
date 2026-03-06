<h2 class="title">Find your perfect acommodation</h2>

<div class="cards-container">
    <?php foreach ($properties as $prop): ?>
        <div class="property-card">
            <div class="property-image">
                <img src="<?=DEFAULT_URL?>assets/<?=$prop['url']?>" alt="Property picture">
            </div>

            <div class="property-info">

                <h4 class="property-title"><?=$prop['title']?></h4>

                <p class="property-location">
                    <svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.66667 6.84C3.66654 7.08806 3.59721 7.33116 3.46648 7.54197C3.33575 7.75278 3.14881 7.92295 2.92667 8.03333L1.74 8.63333C1.51786 8.74372 1.33091 8.91388 1.20019 9.1247C1.06946 9.33551 1.00013 9.57861 1 9.82667V10.3333C1 10.5101 1.07024 10.6797 1.19526 10.8047C1.32029 10.9298 1.48986 11 1.66667 11H9.66667C9.84348 11 10.013 10.9298 10.1381 10.8047C10.2631 10.6797 10.3333 10.5101 10.3333 10.3333V9.82667C10.3332 9.57861 10.2639 9.33551 10.1331 9.1247C10.0024 8.91388 9.81547 8.74372 9.59333 8.63333L8.40667 8.03333C8.18453 7.92295 7.99758 7.75278 7.86685 7.54197C7.73613 7.33116 7.6668 7.08806 7.66667 6.84V4.33333C7.66667 4.15652 7.7369 3.98695 7.86193 3.86193C7.98695 3.7369 8.15652 3.66667 8.33333 3.66667C8.68696 3.66667 9.02609 3.52619 9.27614 3.27614C9.52619 3.02609 9.66667 2.68696 9.66667 2.33333C9.66667 1.97971 9.52619 1.64057 9.27614 1.39052C9.02609 1.14048 8.68696 1 8.33333 1H3C2.64638 1 2.30724 1.14048 2.05719 1.39052C1.80714 1.64057 1.66667 1.97971 1.66667 2.33333C1.66667 2.68696 1.80714 3.02609 2.05719 3.27614C2.30724 3.52619 2.64638 3.66667 3 3.66667C3.17681 3.66667 3.34638 3.7369 3.4714 3.86193C3.59643 3.98695 3.66667 4.15652 3.66667 4.33333V6.84Z" stroke="#3A3A3A" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5.6665 11V14.3333" stroke="#3A3A3A" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <?=$prop['city']?>|<?=$prop['address']?>
                </p>

                <div class="property-details">

                    <div class="rooms-bathrooms-wrap">
                        <span>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.3335 2.66669V13.3334" stroke="#3A3A3A" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M1.3335 5.33331H13.3335C13.6871 5.33331 14.0263 5.47379 14.2763 5.72384C14.5264 5.97389 14.6668 6.31302 14.6668 6.66665V13.3333" stroke="#3A3A3A" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M1.3335 11.3333H14.6668" stroke="#3A3A3A" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4 5.33331V11.3333" stroke="#3A3A3A" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <?=$prop['rooms']?> rooms
                        </span>
                        <span>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.66673 8H13.3334C13.5102 8 13.6798 8.07024 13.8048 8.19526C13.9298 8.32029 14.0001 8.48986 14.0001 8.66667C14.0001 9.55072 13.6489 10.3986 13.0238 11.0237C12.3986 11.6488 11.5508 12 10.6667 12H10.2681C10.2086 12 10.1501 12.0159 10.0989 12.0461C10.0476 12.0763 10.0054 12.1197 9.97652 12.1717C9.94767 12.2238 9.93329 12.2826 9.93485 12.3421C9.93641 12.4016 9.95386 12.4595 9.9854 12.51L11.0147 14.1567C11.0463 14.2071 11.0637 14.2651 11.0653 14.3246C11.0668 14.3841 11.0525 14.4429 11.0236 14.4949C10.9948 14.547 10.9525 14.5903 10.9013 14.6205C10.85 14.6507 10.7916 14.6667 10.7321 14.6667H3.6014C3.5419 14.6667 3.48348 14.6507 3.43221 14.6205C3.38095 14.5903 3.3387 14.547 3.30985 14.4949C3.28101 14.4429 3.26662 14.3841 3.26818 14.3246C3.26974 14.2651 3.2872 14.2071 3.31873 14.1567L4.66673 12" stroke="#3A3A3A" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.33333 12C4.44928 12 3.60143 11.6488 2.97631 11.0237C2.35119 10.3985 2 9.5507 2 8.66665V2.66665C2 2.31302 2.14048 1.97389 2.39052 1.72384C2.64057 1.47379 2.97971 1.33331 3.33333 1.33331H8.66667C9.02029 1.33331 9.35943 1.47379 9.60948 1.72384C9.85952 1.97389 10 2.31302 10 2.66665V7.99998" stroke="#3A3A3A" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <?=$prop['bathrooms']?> bathrooms
                        </span>
                    </div>
                    
                    <span>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_358_1890)">
                            <path d="M14.1999 10.2C14.349 10.3486 14.4674 10.5253 14.5481 10.7197C14.6289 10.9142 14.6705 11.1227 14.6705 11.3333C14.6705 11.5439 14.6289 11.7524 14.5481 11.9469C14.4674 12.1414 14.349 12.318 14.1999 12.4667L12.4665 14.2C12.3179 14.3492 12.1413 14.4675 11.9468 14.5483C11.7523 14.629 11.5438 14.6706 11.3332 14.6706C11.1226 14.6706 10.9141 14.629 10.7196 14.5483C10.5251 14.4675 10.3485 14.3492 10.1999 14.2L1.79987 5.79999C1.50024 5.49891 1.33203 5.09142 1.33203 4.66666C1.33203 4.24189 1.50024 3.83441 1.79987 3.53332L3.5332 1.79999C3.83428 1.50036 4.24177 1.33215 4.66653 1.33215C5.0913 1.33215 5.49879 1.50036 5.79987 1.79999L14.1999 10.2Z" stroke="#3A3A3A" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.6665 8.33333L10.9998 7" stroke="#3A3A3A" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.6665 6.33333L8.99984 5" stroke="#3A3A3A" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5.6665 4.33333L6.99984 3" stroke="#3A3A3A" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M11.6665 10.3333L12.9998 9" stroke="#3A3A3A" stroke-linecap="round" stroke-linejoin="round"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_358_1890">
                            <rect width="16" height="16" fill="white"/>
                            </clipPath>
                            </defs>
                        </svg>
                        <?=$prop['size_m2']?> m²
                    </span>
                </div>

                <div class="property-footer">
                    <span class="property-price"><span><?=$prop['price_per_night']?></span>€/night</span>
                    <a href="<?=DEFAULT_URL?>public/Property/showOne?id=<?=$prop['id']?>" class="property-btn">View property</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div id="toast-container"></div>

    <script src="<?=DEFAULT_URL?>assets/js/toast.js"></script>
    
    <?php if(isset($_SESSION['toast'])): ?>
        <script>
            showToast('<?=$_SESSION['toast']['type']?>','<?= $_SESSION['toast']['message']?>');
        </script>
    <?php endif; ?>
    <?php unset($_SESSION['toast']); ?>
</div>
