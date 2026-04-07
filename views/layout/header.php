<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?=DEFAULT_URL?>assets/css/style.css">
        <link rel="stylesheet" href="<?=DEFAULT_URL?>assets/css/toast.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="" defer></script>
        <script src="<?=DEFAULT_URL?>assets/js/map.js" defer></script>
        <script src="<?=DEFAULT_URL?>assets/js/main.js" defer></script>
        
        <title>Stayly</title>
    </head>
    <body>
        <header>
            <div class="logo">
                <a href="<?=DEFAULT_URL?>/public/index.php">
                    <svg width="189" height="68" viewBox="0 0 189 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_459_478)">
                        <rect width="188.929" height="67.5714" fill="white"/>
                        <path d="M24.5385 0C11.0423 0 0 11.0571 0 24.5714C0 45.4571 24.5385 67.5714 24.5385 67.5714C24.5385 67.5714 49.077 45.4571 49.077 24.5714C49.077 11.0571 38.0346 0 24.5385 0Z" fill="#FF5B8C"/>
                        <path d="M17.1769 27.2429V19.8714H31.9V27.2429" stroke="white" stroke-width="3" stroke-linecap="round"/>
                        <path d="M12.2692 27.2429L24.5385 17.4143L36.8077 27.2429" stroke="white" stroke-width="3" stroke-linecap="round"/>
                        <path d="M98.503 41.5278C97.031 41.5278 95.7083 41.2718 94.535 40.7598C93.383 40.2265 92.4763 39.5012 91.815 38.5838C91.1536 37.6452 90.8123 36.5678 90.791 35.3518H93.895C94.0016 36.3972 94.4283 37.2825 95.175 38.0078C95.943 38.7118 97.0523 39.0638 98.503 39.0638C99.8896 39.0638 100.978 38.7225 101.767 38.0398C102.578 37.3358 102.983 36.4398 102.983 35.3518C102.983 34.4985 102.748 33.8052 102.279 33.2718C101.81 32.7385 101.223 32.3332 100.519 32.0558C99.815 31.7785 98.8656 31.4798 97.671 31.1598C96.199 30.7758 95.015 30.3918 94.119 30.0078C93.2443 29.6238 92.487 29.0265 91.847 28.2158C91.2283 27.3838 90.919 26.2745 90.919 24.8878C90.919 23.6718 91.2283 22.5945 91.847 21.6558C92.4656 20.7172 93.3296 19.9918 94.439 19.4798C95.5696 18.9678 96.8603 18.7118 98.311 18.7118C100.402 18.7118 102.108 19.2345 103.431 20.2798C104.775 21.3252 105.532 22.7118 105.703 24.4398H102.503C102.396 23.5865 101.948 22.8398 101.159 22.1998C100.37 21.5385 99.3243 21.2078 98.023 21.2078C96.807 21.2078 95.815 21.5278 95.047 22.1678C94.279 22.7865 93.895 23.6612 93.895 24.7918C93.895 25.6025 94.119 26.2638 94.567 26.7758C95.0363 27.2878 95.6016 27.6825 96.263 27.9598C96.9456 28.2158 97.895 28.5145 99.111 28.8558C100.583 29.2612 101.767 29.6665 102.663 30.0718C103.559 30.4558 104.327 31.0638 104.967 31.8958C105.607 32.7065 105.927 33.8158 105.927 35.2238C105.927 36.3118 105.639 37.3358 105.063 38.2958C104.487 39.2558 103.634 40.0345 102.503 40.6318C101.372 41.2292 100.039 41.5278 98.503 41.5278ZM113.764 26.1678V36.5038C113.764 37.3572 113.946 37.9652 114.308 38.3278C114.671 38.6692 115.3 38.8398 116.196 38.8398H118.34V41.3038H115.716C114.095 41.3038 112.879 40.9305 112.068 40.1838C111.258 39.4372 110.852 38.2105 110.852 36.5038V26.1678H108.58V23.7678H110.852V19.3518H113.764V23.7678H118.34V26.1678H113.764ZM120.78 32.4718C120.78 30.6798 121.143 29.1118 121.868 27.7678C122.594 26.4025 123.586 25.3465 124.844 24.5998C126.124 23.8532 127.543 23.4798 129.1 23.4798C130.636 23.4798 131.97 23.8105 133.1 24.4718C134.231 25.1332 135.074 25.9652 135.628 26.9678V23.7678H138.572V41.3038H135.628V38.0398C135.052 39.0638 134.188 39.9172 133.036 40.5998C131.906 41.2612 130.583 41.5918 129.068 41.5918C127.511 41.5918 126.103 41.2078 124.844 40.4398C123.586 39.6718 122.594 38.5945 121.868 37.2078C121.143 35.8212 120.78 34.2425 120.78 32.4718ZM135.628 32.5038C135.628 31.1812 135.362 30.0292 134.828 29.0478C134.295 28.0665 133.57 27.3198 132.652 26.8078C131.756 26.2745 130.764 26.0078 129.676 26.0078C128.588 26.0078 127.596 26.2638 126.7 26.7758C125.804 27.2878 125.09 28.0345 124.556 29.0158C124.023 29.9972 123.756 31.1492 123.756 32.4718C123.756 33.8158 124.023 34.9892 124.556 35.9918C125.09 36.9732 125.804 37.7305 126.7 38.2638C127.596 38.7758 128.588 39.0318 129.676 39.0318C130.764 39.0318 131.756 38.7758 132.652 38.2638C133.57 37.7305 134.295 36.9732 134.828 35.9918C135.362 34.9892 135.628 33.8265 135.628 32.5038ZM158.597 23.7678L148.037 49.5598H145.029L148.485 41.1118L141.413 23.7678H144.645L150.149 37.9758L155.589 23.7678H158.597ZM164.437 17.6238V41.3038H161.525V17.6238H164.437ZM184.504 23.7678L173.944 49.5598H170.936L174.392 41.1118L167.32 23.7678H170.552L176.056 37.9758L181.496 23.7678H184.504Z" fill="#FF5B8C"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_459_478">
                        <rect width="188.929" height="67.5714" fill="white"/>
                        </clipPath>
                        </defs>
                    </svg>
                </a>
            </div>
            
            <form action="" method="post" id="headerForm">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_459_491)">
                    <path d="M12.5322 19.0332C13.9297 19.0332 15.2393 18.6113 16.3291 17.8906L20.1787 21.749C20.4336 21.9951 20.7588 22.1182 21.1104 22.1182C21.8398 22.1182 22.376 21.5469 22.376 20.8262C22.376 20.4922 22.2617 20.167 22.0156 19.9209L18.1924 16.0801C18.9834 14.9551 19.4492 13.5928 19.4492 12.1162C19.4492 8.31055 16.3379 5.19922 12.5322 5.19922C8.73535 5.19922 5.61523 8.31055 5.61523 12.1162C5.61523 15.9219 8.72656 19.0332 12.5322 19.0332ZM12.5322 17.1875C9.74609 17.1875 7.46094 14.9023 7.46094 12.1162C7.46094 9.33008 9.74609 7.04492 12.5322 7.04492C15.3184 7.04492 17.6035 9.33008 17.6035 12.1162C17.6035 14.9023 15.3184 17.1875 12.5322 17.1875Z" fill="#FF5B8C"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_459_491">
                    <rect width="28" height="28" fill="white"/>
                    </clipPath>
                    </defs>
                </svg>

                <input type="text" name="search" id="search" placeholder="Search destinations...">

                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <rect opacity="0.6" width="24" height="24" fill="url(#pattern0_459_494)" />
                    <defs>
                        <pattern id="pattern0_459_494" patternContentUnits="objectBoundingBox" width="1" height="1">
                            <use xlink:href="#image0_459_494" transform="scale(0.0416667)" />
                        </pattern>
                        <image id="image0_459_494" width="24" height="24" preserveAspectRatio="none"
                            xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAWElEQVR4nGNgGEDwH4qppY4+FngxMDA8RtL0n0QM0uuJzwJKDP8PxY+o5l1y9NPNgv8U4oG3gFzwf9QCQmDUAoJg1AKCgKB+mhfXnlAFlBjuQdijo4ABAQCYhZ+hvFf0OQAAAABJRU5ErkJggg==" />
                    </defs>
                </svg>
            </form>
            
            <div class="options">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.7406 17C15.6529 17 14.7711 17.8954 14.7711 19C14.7711 20.1046 15.6529 21 16.7406 21C17.8283 21 18.7101 20.1046 18.7101 19C18.7101 17.8954 17.8283 17 16.7406 17ZM16.7406 17H9.15212C8.6981 17 8.47067 17 8.28374 16.918C8.11885 16.8456 7.97579 16.7291 7.87156 16.5805C7.75473 16.414 7.70775 16.1913 7.61474 15.7505L5.19104 4.26465C5.09587 3.81363 5.04765 3.58838 4.92947 3.41992C4.82524 3.27135 4.68221 3.15441 4.51732 3.08205C4.33035 3 4.10419 3 3.64998 3H2.95422M5.90844 6H18.5852C19.296 6 19.651 6 19.8896 6.15036C20.0985 6.28206 20.2515 6.48862 20.3182 6.729C20.3943 7.00343 20.2965 7.34996 20.0995 8.04346L18.736 12.8435C18.6182 13.2581 18.5593 13.465 18.4398 13.6189C18.3344 13.7547 18.1952 13.861 18.0375 13.9263C17.8594 14 17.6476 14 17.2252 14H7.6125M7.87792 21C6.79021 21 5.90844 20.1046 5.90844 19C5.90844 17.8954 6.79021 17 7.87792 17C8.96564 17 9.8474 17.8954 9.8474 19C9.8474 20.1046 8.96564 21 7.87792 21Z" stroke="#FF5B8C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                
                <svg class="user-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.7101 21C18.7101 17.134 15.6239 14 11.8169 14C8.00989 14 4.92371 17.134 4.92371 21M11.8169 11C9.64146 11 7.87793 9.20914 7.87793 7C7.87793 4.79086 9.64146 3 11.8169 3C13.9923 3 15.7558 4.79086 15.7558 7C15.7558 9.20914 13.9923 11 11.8169 11Z" stroke="#FF5B8C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="drop-down drop-down-logged">
                    <a href="<?= DEFAULT_URL ?>public/User/showDashboard?page=dashboard">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 15H6C4.93913 15 3.92172 15.4214 3.17157 16.1716C2.42143 16.9217 2 17.9391 2 19V21" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14.3051 16.53L15.2281 16.148" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15.2281 13.852L14.3051 13.469" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.852 12.228L16.469 11.305" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.852 17.772L16.469 18.696" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M19.1479 12.228L19.5309 11.305" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M19.5299 18.696L19.1479 17.772" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M20.772 13.852L21.696 13.469" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M20.772 16.148L21.696 16.531" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18 18C19.6569 18 21 16.6569 21 15C21 13.3431 19.6569 12 18 12C16.3431 12 15 13.3431 15 15C15 16.6569 16.3431 18 18 18Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                        <span>Profile settings</span>
                    </a>
                    <a href="">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 2V6" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 2V6" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M19 4H5C3.89543 4 3 4.89543 3 6V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V6C21 4.89543 20.1046 4 19 4Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3 10H21" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                        <span>My Bookings</span>
                    </a>
                    
                    <a href="">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 9.49999C2.00002 8.3872 2.33759 7.30058 2.96813 6.38366C3.59867 5.46674 4.49252 4.76265 5.53161 4.36439C6.5707 3.96613 7.70616 3.89244 8.78801 4.15304C9.86987 4.41364 10.8472 4.99627 11.591 5.82399C11.6434 5.88001 11.7067 5.92466 11.7771 5.95519C11.8474 5.98572 11.9233 6.00148 12 6.00148C12.0767 6.00148 12.1526 5.98572 12.2229 5.95519C12.2933 5.92466 12.3566 5.88001 12.409 5.82399C13.1504 4.99089 14.128 4.40336 15.2116 4.1396C16.2952 3.87583 17.4335 3.94834 18.4749 4.34748C19.5163 4.74661 20.4114 5.45345 21.0411 6.3739C21.6708 7.29435 22.0053 8.38475 22 9.49999C22 11.79 20.5 13.5 19 15L13.508 20.313C13.3217 20.527 13.0919 20.6989 12.834 20.8173C12.5762 20.9357 12.296 20.9978 12.0123 20.9996C11.7285 21.0014 11.4476 20.9428 11.1883 20.8277C10.9289 20.7126 10.697 20.5436 10.508 20.332L5 15C3.5 13.5 2 11.8 2 9.49999Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                        <span>Wishlist</span>
                    </a>
                    <a href="">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 17C22 17.5304 21.7893 18.0391 21.4142 18.4142C21.0391 18.7893 20.5304 19 20 19H6.828C6.29761 19.0001 5.78899 19.2109 5.414 19.586L3.212 21.788C3.1127 21.8873 2.9862 21.9549 2.84849 21.9823C2.71077 22.0097 2.56803 21.9956 2.43831 21.9419C2.30858 21.8881 2.1977 21.7971 2.11969 21.6804C2.04167 21.5637 2.00002 21.4264 2 21.286V5C2 4.46957 2.21071 3.96086 2.58579 3.58579C2.96086 3.21071 3.46957 3 4 3H20C20.5304 3 21.0391 3.21071 21.4142 3.58579C21.7893 3.96086 22 4.46957 22 5V17Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                        <span>Messages</span>
                    </a>
                    <a href="">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 5H4C2.89543 5 2 5.89543 2 7V17C2 18.1046 2.89543 19 4 19H20C21.1046 19 22 18.1046 22 17V7C22 5.89543 21.1046 5 20 5Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 10H22" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                        <span>Payments</span>
                    </a>

                    <a href="<?= DEFAULT_URL ?>public/User/logout">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 17L21 12L16 7" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21 12H9" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        
                        <span>Log out</span>
                    </a>
                </div>
            <?php else: ?>
                <div class="drop-down drop-down-default ">
                    <a href="<?=DEFAULT_URL?>public/Home/showLogin">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 17L15 12L10 7" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 12H3" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H15" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                        <span>Log in</span>
                    </a>
                    <a href="<?=DEFAULT_URL?>public/Home/showRegister">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 21V19C16 17.9391 15.5786 16.9217 14.8284 16.1716C14.0783 15.4214 13.0609 15 12 15H6C4.93913 15 3.92172 15.4214 3.17157 16.1716C2.42143 16.9217 2 17.9391 2 19V21" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M19 8V14" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 11H16" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                        <span>Register</span>
                    </a>
                    <a href="<?= DEFAULT_URL ?>public/Home/showHost">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 21V13C15 12.7348 14.8946 12.4804 14.7071 12.2929C14.5196 12.1054 14.2652 12 14 12H10C9.73478 12 9.48043 12.1054 9.29289 12.2929C9.10536 12.4804 9 12.7348 9 13V21" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3 10C2.99993 9.70907 3.06333 9.42163 3.18579 9.15772C3.30824 8.89382 3.4868 8.6598 3.709 8.47201L10.709 2.47201C11.07 2.16692 11.5274 1.99953 12 1.99953C12.4726 1.99953 12.93 2.16692 13.291 2.47201L20.291 8.47201C20.5132 8.6598 20.6918 8.89382 20.8142 9.15772C20.9367 9.42163 21.0001 9.70907 21 10V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V10Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                        <span>Become a host</span>
                    </a>
                </div>
            <?php endif; ?>
            </div>
        </header>
        <main>