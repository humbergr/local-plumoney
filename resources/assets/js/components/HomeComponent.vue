<template>
    <div>     
        <header>
            <nav id="main-navbar" class="navbar navbar-expand-lg navbar-dark border-bottom border-light-alpha fixed-top">
                    <div class="container-fluid">
                        <a class="navbar-brand mr-0" href="/">
                            <img src="/assets/img/brand/akb-logo--light.svg" height="46" alt="AKB Fintech - Creciendo contigo" title="AKB Fintech - Creciendo contigo">
                        </a>

                        <button id="menu-open" class="offcanvas-menu__toggler" type="button" data-target="#offcanvas-menu" aria-controls="offcanvas-menu" aria-label="Toggle navigation">
                            <svg width="1.5rem" height="1.5rem" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="1" y="6" width="20" height="2" rx="1" fill="#ffffff"/>
                                <rect x="9" y="22" width="20" height="2" rx="1" fill="#ffffff"/>
                                <rect x="1" y="14" width="28" height="2" rx="1" fill="#ffffff"/>
                            </svg>
                        </button>

                        <div id="offcanvas-menu" class="offcanvas-menu align-items-lg-center flex-column flex-lg-row">
                            <div class="offcanvas-menu__header d-lg-none order-1 py-2 mb-4">

                                <button id="menu-close" class="offcanvas-menu__toggler" type="button" data-target="#offcanvas-menu" aria-controls="offcanvas-menu" aria-label="Toggle navigation">
                                    <svg width="1.5rem" height="1.5rem" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.67488 5.28736C6.29173 4.90421 5.67052 4.90421 5.28736 5.28736C4.90421 5.67052 4.90421 6.29173 5.28736 6.67488L13.6125 15L5.28743 23.3251C4.90427 23.7083 4.90427 24.3295 5.28743 24.7126C5.67058 25.0958 6.29179 25.0958 6.67494 24.7126L15 16.3876L23.3251 24.7126C23.7083 25.0958 24.3295 25.0958 24.7126 24.7126C25.0958 24.3295 25.0958 23.7083 24.7126 23.3251L16.3875 15L24.7127 6.67488C25.0958 6.29173 25.0958 5.67052 24.7127 5.28736C24.3295 4.90421 23.7083 4.90421 23.3252 5.28736L15 13.6125L6.67488 5.28736Z" fill="#C4C4C4"/>
                                    </svg>
                                </button>
                                <a href="" class="d-block">
                                    <img src="/assets/img/brand/akb-logo--light.svg" height="46" alt="AKB Fintech - Creciendo contigo" class="logo">
                                </a>
                            </div>
                            
                            <ul class="navbar-nav order-3 order-lg-2 mr-lg-auto">
                                <li class="nav-item mr-1">
                                    <router-link class="link-to nav-link px-4 px-lg-2" :to="{name: 'home'}">Home</router-link>
                                </li>
                                <li class="nav-item mr-1 dropdown ">
                                    <a class="nav-link px-4 px-lg-2" href="#" id="aboutUs-dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Nosotros
                                        <svg width="12px" height="12px" viewBox="0 0 16 16" class="bi bi-chevron-down align-base" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="aboutUs-dropdown">
                                        <router-link class="link-to dropdown-item" :to="{name: 'about'}">
                                            <span class="dropdown-item-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0)">
                                                    <path d="M23.9967 17.9958C20.8286 17.3497 16.6922 16.5483 12.1636 16.5022C7.48299 16.4546 3.2155 17.1958 0 17.8182C0 17.8182 4.63598 16.8322 12.1652 16.9091C19.6945 16.9861 24 18 24 18" fill="currentColor"/>
                                                    <path opacity="0.25" d="M4.89798 15.3978V15.4069H6.84057L6.84965 14.2471V14.2349V14.1486V14.0941V12.0334C6.84965 10.4255 6.95715 9.2036 6.2107 8.37084C6.20162 8.35873 6.19102 8.34813 6.18193 8.33753L6.14711 8.29514C6.11229 8.26031 6.07746 8.227 6.03961 8.19521L5.9957 8.16795C5.97299 8.14827 5.94725 8.13313 5.92302 8.11345L5.86549 8.07105C5.73216 7.97622 5.58962 7.89507 5.44003 7.8288C5.37643 7.80003 5.31284 7.77277 5.24925 7.74855L5.21443 7.73644C4.7554 7.57453 4.27329 7.48762 3.78663 7.47904C3.6216 7.46995 3.45353 7.4639 3.27487 7.4639C2.82774 7.47167 2.38161 7.50911 1.93944 7.57594C1.4861 7.63381 1.0394 7.73522 0.605516 7.87876V9.30504C0.971988 9.1766 1.35116 9.08777 1.73655 9.04007C2.1437 8.97321 2.55502 8.93477 2.96751 8.925C3.35854 8.90859 3.74984 8.94992 4.12882 9.04764C4.24201 9.08086 4.34638 9.13887 4.43438 9.21742C4.52236 9.29599 4.59177 9.39316 4.63756 9.50187C4.71264 9.67952 4.75472 9.86936 4.76171 10.0621L4.78442 10.536H3.22188C2.87326 10.536 2.51743 10.5527 2.16948 10.594C1.54064 10.6687 1.00827 10.8479 0.605516 11.2461C0.0816389 11.7624 -0.00012207 12.5558 -0.00012207 13.0721C-0.00012207 13.0888 -0.00012207 13.1039 -0.00012207 13.1206C0.000226172 13.2365 0.0077966 13.3523 0.0225893 13.4673C0.0316739 13.5445 0.0392445 13.5899 0.0392445 13.5899C0.127259 14.0646 0.379114 14.4933 0.75087 14.8012C1.25556 15.2282 1.92782 15.4417 2.76765 15.4417C3.5245 15.4676 4.27016 15.2535 4.89798 14.83V15.3251V15.3978ZM4.06371 13.9034C3.79961 13.9637 3.52906 13.9911 3.25821 13.9851C2.91878 14.0044 2.58246 13.9113 2.3013 13.7201C2.18819 13.6351 2.09749 13.5238 2.03708 13.3959C1.97667 13.2679 1.94834 13.1272 1.95458 12.9858C1.94807 12.8453 1.97847 12.7055 2.04276 12.5804C2.10703 12.4553 2.20296 12.3492 2.32099 12.2727C2.56627 12.1091 2.89634 12.041 3.48684 12.041L4.82379 12.0213L4.8677 13.5354C4.61674 13.6912 4.34644 13.8134 4.06371 13.8988V13.9034Z" fill="currentColor"/>
                                                    <path d="M4.78453 10.5269L3.9151 10.5343L4.82416 12.0319L4.78453 10.5269Z" fill="currentColor"/>
                                                    <path opacity="0.25" d="M4.82467 12.0319L3.01544 12.0603L3.91444 10.5331L4.82467 12.0319Z" fill="currentColor"/>
                                                    <path d="M2.16962 10.5933C2.49584 10.5557 2.82389 10.536 3.15226 10.5342H3.91385L3.4899 11.258L3.01599 12.0605L2.16962 10.5933Z" fill="currentColor"/>
                                                    <path opacity="0.25" d="M7.91711 15.406H9.87333V7.64777L7.91711 8.82725V15.406Z" fill="currentColor"/>
                                                    <path opacity="0.25" d="M12.302 11.1462L15.3181 7.62134H12.9546L9.99908 11.1462L13.0969 15.4462H15.4604L12.302 11.1462Z" fill="currentColor"/>
                                                    <path d="M9.87314 5.3154L7.92147 6.48234L7.91693 4.13895L9.87314 5.3154Z" fill="currentColor"/>
                                                    <path opacity="0.25" d="M9.87328 5.31537V7.64864L7.92194 6.48172L9.87328 5.31537Z" fill="currentColor"/>
                                                    <path d="M9.87377 7.64804L7.92168 8.82556V6.48108L9.87377 7.64804Z" fill="currentColor"/>
                                                    <path opacity="0.25" d="M23.4714 9.03855C23.1457 8.43229 22.6303 7.9494 22.0042 7.66375C21.4316 7.40853 20.814 7.26959 20.1873 7.25494C19.3859 7.25494 18.7737 7.3632 18.3396 7.58123L16.3778 8.76299V15.4053H17.9055L17.9252 14.774C18.5507 15.1602 19.2691 15.3695 20.0041 15.3796C21.2688 15.3796 22.2505 15.0162 22.949 14.2894C23.6475 13.5627 23.9972 12.5674 23.9983 11.3036C24.0201 10.5159 23.8387 9.73579 23.4714 9.03855ZM21.4031 13.2356C20.9993 13.6505 20.4254 13.8579 19.6816 13.8579C19.2039 13.8687 18.7345 13.7323 18.3371 13.4673V9.04915C18.7789 8.85427 19.2596 8.76361 19.7421 8.78418C21.2502 8.78418 22.0042 9.66035 22.0042 11.4127C22.0042 12.2131 21.8039 12.8208 21.4031 13.2356Z" fill="currentColor"/>
                                                    <path d="M16.3778 8.76293V6.43146L18.3402 7.58063L16.3778 8.76293Z" fill="currentColor"/>
                                                    <path d="M18.3396 5.25566L16.3772 6.43086V4.07986L18.3396 5.25566Z" fill="currentColor"/>
                                                    <path opacity="0.25" d="M18.3403 5.25629V7.58118L16.378 6.43083L18.3403 5.25629Z" fill="currentColor"/>
                                                    </g>
                                                    <defs>
                                                    </defs>
                                                </svg>
                                            </span> Sobre nosotros
                                        </router-link>
                                        <router-link class="link-to dropdown-item" :to="{name: 'mia'}">
                                            <span class="dropdown-item-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.25" d="M0.005134 7.69662H7.99316C9.15337 7.69662 9.98503 7.94382 10.4984 8.4382C11.0118 8.93258 11.2633 9.73595 11.2633 10.8539V16.3202H9.16877V11.1517C9.16877 10.5168 9.0815 10.1011 8.90695 9.89887C8.73241 9.69662 8.37305 9.5955 7.82374 9.5955H6.65326V16.3258H4.52791V9.58988H2.09454V16.3202H0V7.69662H0.005134Z" fill="currentColor"/>
                                                    <path opacity="0.25" d="M14.8056 16.3034H12.6238V7.67978H14.8056V16.3034Z" fill="currentColor"/>
                                                    <path d="M23.1837 8.49438C22.6498 7.96629 21.8592 7.69663 20.8171 7.6854V6H19.1692V7.6854H16.4894V9.46629H19.9649C20.2986 9.46629 20.5809 9.48876 20.8171 9.5337C21.084 9.58427 21.2894 9.66854 21.4331 9.77528C21.7103 9.98314 21.8438 10.3427 21.8438 10.8539V11.0674H19.1076C17.9833 11.0674 17.1773 11.2809 16.6947 11.7079C16.2122 12.1348 15.9709 12.8371 15.9709 13.8146C15.9709 14.6067 16.1814 15.2191 16.6023 15.6517C17.0233 16.0843 17.6239 16.2978 18.4094 16.2978H19.1692V18H20.8171V16.2978H22.6293C23.3942 16.2528 24 15.5562 24 14.7079V10.8989C24 9.83708 23.7279 9.0337 23.1837 8.49438ZM21.8592 13.9607C21.8592 14.2978 21.6128 14.5674 21.3048 14.5674H19.2564C19.2256 14.5674 19.2 14.5674 19.1692 14.5674C18.7585 14.5618 18.4864 14.5 18.3478 14.3876C18.1989 14.2697 18.127 14.0562 18.127 13.7528C18.127 13.3933 18.2194 13.1461 18.4043 13C18.5583 12.882 18.8149 12.809 19.1743 12.7921C19.2513 12.7865 19.3283 12.7865 19.4105 12.7865H21.8644V13.9607H21.8592Z" fill="currentColor"/>
                                                </svg>
                                            </span> Billetera MIA
                                        </router-link>
                                        <router-link class="link-to dropdown-item" :to="{name: 'security'}">
                                            <span class="dropdown-item-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.25" fill-rule="evenodd" clip-rule="evenodd" d="M7.78057 1.5375C9.34507 1.1235 11.0056 0.75 12.0001 0.75C12.9931 0.75 14.6551 1.1235 16.2196 1.5375C17.615 1.91216 19.0014 2.31978 20.3776 2.76C21.1696 3.012 21.7666 3.6945 21.8821 4.536C22.7416 10.8315 20.7481 15.4965 18.3316 18.5835C17.3018 19.9083 16.0792 21.0712 14.7046 22.0335C14.2266 22.3683 13.7213 22.6623 13.1941 22.9125C12.7891 23.0985 12.3571 23.25 12.0001 23.25C11.6431 23.25 11.2111 23.0985 10.8061 22.9125C10.2787 22.6626 9.77342 22.3685 9.29557 22.0335C7.92148 21.0711 6.69941 19.9082 5.67007 18.5835C3.25057 15.4965 1.25857 10.8315 2.11807 4.536C2.17554 4.12811 2.34734 3.74483 2.6136 3.43052C2.87985 3.11622 3.22967 2.88374 3.62257 2.76C4.99874 2.31979 6.38513 1.91217 7.78057 1.5375ZM12.7726 11.8635C13.2671 11.6825 13.6826 11.3338 13.9465 10.8781C14.2104 10.4223 14.3061 9.88844 14.2169 9.36941C14.1277 8.85037 13.8593 8.37907 13.4583 8.03759C13.0574 7.69612 12.5494 7.5061 12.0228 7.50063C11.4961 7.49517 10.9843 7.6746 10.5764 8.00769C10.1684 8.34077 9.89026 8.8064 9.79031 9.32347C9.69035 9.84054 9.77495 10.3763 10.0294 10.8374C10.2838 11.2985 10.6919 11.6558 11.1826 11.847C11.1509 11.9115 11.1287 11.9802 11.1166 12.051L10.6171 15.051C10.5992 15.1584 10.605 15.2684 10.6339 15.3733C10.6628 15.4783 10.7143 15.5757 10.7846 15.6588C10.855 15.7418 10.9426 15.8086 11.0414 15.8544C11.1401 15.9002 11.2477 15.924 11.3566 15.924H12.6091C12.7184 15.9241 12.8264 15.9002 12.9255 15.8542C13.0247 15.8081 13.1125 15.7409 13.183 15.6573C13.2534 15.5737 13.3048 15.4757 13.3334 15.3702C13.362 15.2647 13.3672 15.1542 13.3486 15.0465L12.8281 12.0465C12.8175 11.9834 12.7988 11.9219 12.7726 11.8635Z" fill="currentColor"/>
                                                    <path d="M13.9465 10.8781C13.6826 11.3338 13.2672 11.6825 12.7726 11.8635C12.7988 11.9219 12.8175 11.9834 12.8281 12.0465L13.3486 15.0465C13.3672 15.1542 13.362 15.2647 13.3334 15.3702C13.3048 15.4757 13.2535 15.5737 13.183 15.6573C13.1126 15.7409 13.0247 15.8081 12.9256 15.8542C12.8264 15.9002 12.7184 15.9241 12.6091 15.924H11.3566C11.2477 15.924 11.1402 15.9002 11.0414 15.8544C10.9427 15.8086 10.855 15.7418 10.7847 15.6588C10.7143 15.5757 10.6629 15.4783 10.6339 15.3734C10.605 15.2684 10.5993 15.1584 10.6171 15.051L11.1166 12.051C11.1287 11.9802 11.151 11.9115 11.1826 11.847C10.6919 11.6558 10.2838 11.2985 10.0294 10.8374C9.77499 10.3763 9.6904 9.84055 9.79035 9.32348C9.89031 8.80641 10.1685 8.34078 10.5764 8.0077C10.9843 7.67461 11.4962 7.49518 12.0228 7.50064C12.5494 7.5061 13.0574 7.69612 13.4584 8.0376C13.8593 8.37908 14.1277 8.85038 14.2169 9.36942C14.3061 9.88845 14.2105 10.4223 13.9465 10.8781Z" fill="currentColor"/>
                                                </svg>
                                            </span> Seguridad
                                        </router-link>
                                        <router-link class="link-to dropdown-item" :to="{name: 'promotor'}">
                                            <span class="dropdown-item-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0)">
                                                    <path opacity="0.25" fill-rule="evenodd" clip-rule="evenodd" d="M0 18.7501C0 19.3469 0.237053 19.9192 0.65901 20.3411C1.08097 20.7631 1.65326 21.0001 2.25 21.0001H21.75C22.3467 21.0001 22.919 20.7631 23.341 20.3411C23.7629 19.9192 24 19.3469 24 18.7501V10.2751L12.1935 13.4206C12.0667 13.4545 11.9333 13.4545 11.8065 13.4206L0 10.2751V18.7501Z" fill="currentColor"/>
                                                    <path opacity="0.25" d="M0.65901 5.15901C0.237053 5.58097 0 6.15326 0 6.75V8.826L11.421 11.871C11.8004 11.972 12.1996 11.972 12.579 11.871L24 8.826V6.75C24 6.15326 23.7629 5.58097 23.341 5.15901C22.919 4.73705 22.3467 4.5 21.75 4.5H2.25C1.65326 4.5 1.08097 4.73705 0.65901 5.15901Z" fill="currentColor"/>
                                                    <path d="M8.5 1.5C7.94772 1.5 7.5 1.94772 7.5 2.5V4.5H9V3.5C9 3.22386 9.22386 3 9.5 3H14.5C14.7761 3 15 3.22386 15 3.5V4.5H16.5V2.5C16.5 1.94772 16.0523 1.5 15.5 1.5H8.5Z" fill="currentColor"/>
                                                    <path d="M11.9958 11.9505L-0.00488281 8.8297V10.277L11.9958 13.4581L23.989 10.277V8.82216L11.9958 11.9505Z" fill="currentColor"/>
                                                    </g>
                                                    <defs>
                                                    <clipPath id="clip0">
                                                    <rect width="24" height="24" fill="white"/>
                                                    </clipPath>
                                                    </defs>
                                                </svg>
                                            </span> Se parte de AKB
                                        </router-link>
                                    </div>
                                </li>
                                <li class="nav-item mr-1 ">
                                    <router-link class="link-to nav-link px-4 px-lg-2" :to="{name: 'business'}">Negocios</router-link>
                                </li>
                                <li class="nav-item mr-1 ">
                                    <a class="nav-link px-4 px-lg-2" href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon">Blog</a>
                                </li>
                                <li class="nav-item mr-1 ">
                                    <a class="nav-link px-4 px-lg-2" href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon">Ayuda</a>
                                </li>
                                <li class="nav-item mr-1 ">
                                    <router-link class="link-to nav-link px-4 px-lg-2" :to="{name: 'contact'}">Contacto</router-link>
                                </li>
                            </ul>

                            <ul v-if="user != null" class="navbar-nav flex-row order-2 order-lg-3 mb-3 mb-lg-0">
                                <li class="nav-item dropdown">
                                    <a href="javascript:void(0);" style="color: #fff;"
                                    class="dropdown-toggle"
                                    id="dropdownUserLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-user mr-2"></i>{{user.name}}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownUserLink">
                                        <a class="dropdown-item" href="/send-money">Enviar Dinero</a>
                                        <a class="dropdown-item" href="/convert-money">Convertir Dinero</a>
                                        <a class="dropdown-item" href="/wallets">Billeteras</a>
                                        <a class="dropdown-item" href="/user-info">Configuración</a>
                                        <a class="dropdown-item" href="/transactions-history">Historial de transacciones</a>
                                    </div>
                                </li>
                                <li class="nav-item mx-1">/</li>
                                <li class="nav-item">
                                    <a href="/logout" class="text-secondary">Salir</a>
                                </li>
                            </ul>

                            <ul v-else class="navbar-nav flex-row order-2 order-lg-3 mb-3 mb-lg-0">
                                <li class="nav-item flex-grow-1 mx-1">
                                    <a :href="getUrl('/login')" class="btn btn-outline-light btn-sm d-block d-lg-inline-block text-white px-lg-3 py-2 py-lg-1">
                                        Ingresar
                                    </a>
                                </li>
                                <li class="nav-item flex-grow-1 mx-1">
                                    <a :href="getUrl('/signin')" class="btn btn-secondary btn-gradient btn-sm d-block d-lg-inline-block ml-md-2 px-lg-3 py-2 py-lg-1">Crear cuenta</a>
                                </li>
                            </ul>
                        </div>

                        <ul class="navbar-nav" >
                            <li class="nav-item d-lg-none">
                                <a :href="getUrl('/login')" class="nav-link nav-link--rounded px-2" title="Ingresar">
                                    <svg width="2rem" height="2rem" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.25" d="M4 3C4 2.44772 4.44772 2 5 2H12C13.1046 2 14 2.89543 14 4V12C14 13.1046 13.1046 14 12 14H5C4.44772 14 4 13.5523 4 13V12V8.5V5V3Z" fill="white"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.41122 5.11932L8.8861 7.71193C9.03797 7.87103 9.03797 8.12897 8.8861 8.28807L6.41122 10.8807C6.25935 11.0398 6.01312 11.0398 5.86125 10.8807C5.70938 10.7216 5.70938 10.4636 5.86125 10.3045L7.67225 8.40739H2.38889C2.17411 8.40739 2 8.22499 2 8C2 7.77501 2.17411 7.59261 2.38889 7.59261H7.67225L5.86125 5.69546C5.70938 5.53636 5.70938 5.27842 5.86125 5.11932C6.01312 4.96023 6.25935 4.96023 6.41122 5.11932Z" fill="white"/>
                                        <path d="M11 7C11 6.44772 11.4477 6 12 6V6C12.5523 6 13 6.44772 13 7V9C13 9.55228 12.5523 10 12 10V10C11.4477 10 11 9.55228 11 9V7Z" fill="white"/>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
            </nav>           
        </header>
                    
        <router-view></router-view>
        <vue-progress-bar></vue-progress-bar>

        <footer class="footer">
            <div class="container">
                <!-- footer top -->
                <div class="d-flex justify-content-between flex-wrap py-5 py-md-8">
                        <div class="mb-4 mb-md-0">
                            <a href="/" class="footer__brand">
                                <img src="/assets/img/brand/akb-logo--light.svg" class="img-fluid" title="AKB Fintech" alt="AKB Fintech" style="max-height: 60px;">
                            </a>
                        </div>
                        <ul class="list-inline text-wrap">
                            <li class="list-inline-item">
                                <a href="mailto:ccare@akbfintech.com" class="footer__contactPill mb-2 mb-md-0">
                                    ccare@akbfintech.com
                                    <div class="footer__contactPill__icon">
                                        <svg class="bi bi-envelope-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                                        </svg>
                                    </div>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="tel:+17862458123" class="footer__contactPill mb-2 mb-md-0">
                                    +1 (786) 245-8123
                                    <div class="footer__contactPill__icon">
                                        <svg class="bi bi-headphones" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M8 3a5 5 0 0 0-5 5v4.5H2V8a6 6 0 1 1 12 0v4.5h-1V8a5 5 0 0 0-5-5z"/>
                                            <path d="M11 10a1 1 0 0 1 1-1h2v4a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-3zm-6 0a1 1 0 0 0-1-1H2v4a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-3z"/>
                                        </svg>
                                    </div>
                                </a>
                            </li>
                        </ul>
                </div>
                <!-- footer middle -->
                <div class="pb-5 pb-md-7">
                        <div class="row">
                            <div class="col-6 col-md-2">
                                <div class="footer__menu__title mb-3 mb-md-4 fs-14">Sobre</div>
                                <ul class="footer__menu list-unstyled mb-4 mb-md-3">
                                    <li>
                                        <router-link class="footer__menu__link mb-2 mb-md-3" :to="{name: 'about'}">Nosotros</router-link>
                                    </li>
                                    <li>
                                        <router-link class="footer__menu__link mb-2 mb-md-3" :to="{name: 'mia'}">Billetera MIA</router-link>
                                    </li>
                                    <li>
                                        <router-link class="footer__menu__link mb-2 mb-md-3" :to="{name: 'security'}">Seguridad</router-link>
                                    </li>
                                    <li>
                                        <router-link class="footer__menu__link mb-2 mb-md-3" :to="{name: 'promotor'}">Se parte de AKB</router-link>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="footer__menu__title mb-3 mb-md-4 fs-14">Empresas</div>
                                <ul class="footer__menu list-unstyled mb-4 mb-md-3">
                                    <li>
                                        <a href="javascript:void(0)" class="footer__menu__link mb-2 mb-md-3">Empresas</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="footer__menu__link mb-2 mb-md-3">Paga</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="footer__menu__link mb-2 mb-md-3">Recibe</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="footer__menu__link mb-2 mb-md-3">Convierte</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="footer__menu__title mb-3 mb-md-4 fs-14">Enlaces</div>
                                <ul class="footer__menu list-unstyled mb-4 mb-md-3">
                                    <li>
                                        <a href="javascript:void(0)" class="footer__menu__link mb-2 mb-md-3">Blog</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="footer__menu__link mb-2 mb-md-3">Foro</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="footer__menu__link mb-2 mb-md-3">Ingresar</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="footer__menu__link mb-2 mb-md-3">Registrarse</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="footer__menu__title mb-3 mb-md-4 fs-14">Ayuda</div>
                                <ul class="footer__menu list-unstyled mb-4 mb-md-3">
                                    <li>
                                        <a href="javascript:void(0)" class="footer__menu__link mb-2 mb-md-3">Preguntas frecuentes</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="footer__menu__link mb-2 mb-md-3">Centro de ayuda</a>
                                    </li>
                                    <li>
                                        <router-link class="footer__menu__link mb-2 mb-md-3" :to="{name: 'contact'}">Contacto</router-link>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <div class="footer__menu__title mb-3 mb-md-4 fs-14">Síguenos</div>
                                <ul class="footer__menu list-unstyled">
                                    <li class="list-inline-item">
                                        <a href="https://www.facebook.com/akryptosbankenespanol/" class="footer__socialLink mb-2 mb-md-3" target="_blank">
                                            <svg class="footer__socialLink__icon" width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8.71875 9H6.375V16H3.25V9H0.71875V6.09375H3.25V3.90625C3.25 3.07292 3.40625 2.36458 3.71875 1.78125C4.03125 1.19792 4.46875 0.760417 5.03125 0.46875C5.61458 0.15625 6.28125 0 7.03125 0C7.36458 0 7.71875 0.0208333 8.09375 0.0625C8.46875 0.0833333 8.76042 0.114583 8.96875 0.15625L9.28125 0.1875V2.65625H8.03125C7.44792 2.65625 7.02083 2.8125 6.75 3.125C6.5 3.41667 6.375 3.78125 6.375 4.21875V6.09375H9.15625L8.71875 9Z" fill="white"/>
                                            </svg>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="https://twitter.com/AkryptosBankEsp" class="footer__socialLink mb-2 mb-md-3" target="_blank">
                                            <svg class="footer__socialLink__icon" width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.3438 3.75C14.3646 3.83333 14.375 3.96875 14.375 4.15625C14.375 5.26042 14.1667 6.36458 13.75 7.46875C13.3333 8.55208 12.7396 9.55208 11.9688 10.4688C11.2188 11.3646 10.2396 12.0938 9.03125 12.6562C7.82292 13.2188 6.48958 13.5 5.03125 13.5C3.19792 13.5 1.52083 13.0104 0 12.0312C0.229167 12.0521 0.489583 12.0625 0.78125 12.0625C2.30208 12.0625 3.66667 11.5938 4.875 10.6562C4.14583 10.6562 3.5 10.4479 2.9375 10.0312C2.39583 9.59375 2.02083 9.05208 1.8125 8.40625C2.02083 8.42708 2.21875 8.4375 2.40625 8.4375C2.69792 8.4375 2.98958 8.40625 3.28125 8.34375C2.78125 8.23958 2.33333 8.03125 1.9375 7.71875C1.54167 7.40625 1.22917 7.03125 1 6.59375C0.770833 6.13542 0.65625 5.64583 0.65625 5.125V5.0625C1.11458 5.33333 1.60417 5.47917 2.125 5.5C1.14583 4.83333 0.65625 3.91667 0.65625 2.75C0.65625 2.16667 0.8125 1.61458 1.125 1.09375C1.9375 2.11458 2.92708 2.92708 4.09375 3.53125C5.28125 4.13542 6.54167 4.46875 7.875 4.53125C7.83333 4.28125 7.8125 4.03125 7.8125 3.78125C7.8125 2.88542 8.125 2.11458 8.75 1.46875C9.39583 0.822917 10.1667 0.5 11.0625 0.5C12.0208 0.5 12.8229 0.84375 13.4688 1.53125C14.2188 1.38542 14.9167 1.125 15.5625 0.75C15.3125 1.52083 14.8333 2.125 14.125 2.5625C14.75 2.47917 15.375 2.30208 16 2.03125C15.5417 2.69792 14.9896 3.27083 14.3438 3.75Z" fill="white"/>
                                            </svg>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="https://www.instagram.com/akbfintech/" class="footer__socialLink mb-2 mb-md-3" target="_blank">
                                            <svg class="footer__socialLink__icon" width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.46875 4.46875C5.17708 3.76042 6.03125 3.40625 7.03125 3.40625C8.03125 3.40625 8.875 3.76042 9.5625 4.46875C10.2708 5.15625 10.625 6 10.625 7C10.625 8 10.2708 8.85417 9.5625 9.5625C8.875 10.25 8.03125 10.5938 7.03125 10.5938C6.03125 10.5938 5.17708 10.25 4.46875 9.5625C3.78125 8.85417 3.4375 8 3.4375 7C3.4375 6 3.78125 5.15625 4.46875 4.46875ZM5.375 8.65625C5.83333 9.11458 6.38542 9.34375 7.03125 9.34375C7.67708 9.34375 8.22917 9.11458 8.6875 8.65625C9.14583 8.19792 9.375 7.64583 9.375 7C9.375 6.35417 9.14583 5.80208 8.6875 5.34375C8.22917 4.88542 7.67708 4.65625 7.03125 4.65625C6.38542 4.65625 5.83333 4.88542 5.375 5.34375C4.91667 5.80208 4.6875 6.35417 4.6875 7C4.6875 7.64583 4.91667 8.19792 5.375 8.65625ZM11.3438 2.6875C11.5104 2.83333 11.5938 3.02083 11.5938 3.25C11.5938 3.47917 11.5104 3.67708 11.3438 3.84375C11.1979 4.01042 11.0104 4.09375 10.7812 4.09375C10.5521 4.09375 10.3542 4.01042 10.1875 3.84375C10.0208 3.67708 9.9375 3.47917 9.9375 3.25C9.9375 3.02083 10.0208 2.83333 10.1875 2.6875C10.3542 2.52083 10.5521 2.4375 10.7812 2.4375C11.0104 2.4375 11.1979 2.52083 11.3438 2.6875ZM14 4.125C14.0208 4.6875 14.0312 5.64583 14.0312 7C14.0312 8.35417 14.0208 9.3125 14 9.875C13.9375 11.1458 13.5521 12.1354 12.8438 12.8438C12.1562 13.5312 11.1771 13.8958 9.90625 13.9375C9.34375 13.9792 8.38542 14 7.03125 14C5.67708 14 4.71875 13.9792 4.15625 13.9375C2.88542 13.875 1.90625 13.5 1.21875 12.8125C0.947917 12.5625 0.729167 12.2708 0.5625 11.9375C0.395833 11.6042 0.270833 11.2812 0.1875 10.9688C0.125 10.6562 0.09375 10.2917 0.09375 9.875C0.0520833 9.3125 0.03125 8.35417 0.03125 7C0.03125 5.64583 0.0520833 4.67708 0.09375 4.09375C0.15625 2.84375 0.53125 1.875 1.21875 1.1875C1.90625 0.479167 2.88542 0.09375 4.15625 0.03125C4.71875 0.0104167 5.67708 0 7.03125 0C8.38542 0 9.34375 0.0104167 9.90625 0.03125C11.1771 0.09375 12.1562 0.479167 12.8438 1.1875C13.5521 1.875 13.9375 2.85417 14 4.125ZM12.5 11.125C12.5625 10.9583 12.6146 10.75 12.6562 10.5C12.6979 10.2292 12.7292 9.91667 12.75 9.5625C12.7708 9.1875 12.7812 8.88542 12.7812 8.65625C12.7812 8.42708 12.7812 8.10417 12.7812 7.6875C12.7812 7.27083 12.7812 7.04167 12.7812 7C12.7812 6.9375 12.7812 6.70833 12.7812 6.3125C12.7812 5.89583 12.7812 5.57292 12.7812 5.34375C12.7812 5.11458 12.7708 4.82292 12.75 4.46875C12.7292 4.09375 12.6979 3.78125 12.6562 3.53125C12.6146 3.26042 12.5625 3.04167 12.5 2.875C12.25 2.22917 11.8021 1.78125 11.1562 1.53125C10.9896 1.46875 10.7708 1.41667 10.5 1.375C10.25 1.33333 9.9375 1.30208 9.5625 1.28125C9.20833 1.26042 8.91667 1.25 8.6875 1.25C8.47917 1.25 8.15625 1.25 7.71875 1.25C7.30208 1.25 7.07292 1.25 7.03125 1.25C6.98958 1.25 6.76042 1.25 6.34375 1.25C5.92708 1.25 5.60417 1.25 5.375 1.25C5.14583 1.25 4.84375 1.26042 4.46875 1.28125C4.11458 1.30208 3.80208 1.33333 3.53125 1.375C3.28125 1.41667 3.07292 1.46875 2.90625 1.53125C2.26042 1.78125 1.8125 2.22917 1.5625 2.875C1.5 3.04167 1.44792 3.26042 1.40625 3.53125C1.36458 3.78125 1.33333 4.09375 1.3125 4.46875C1.29167 4.82292 1.28125 5.11458 1.28125 5.34375C1.28125 5.55208 1.28125 5.875 1.28125 6.3125C1.28125 6.72917 1.28125 6.95833 1.28125 7C1.28125 7.08333 1.28125 7.28125 1.28125 7.59375C1.28125 7.88542 1.28125 8.13542 1.28125 8.34375C1.28125 8.53125 1.28125 8.78125 1.28125 9.09375C1.30208 9.40625 1.32292 9.67708 1.34375 9.90625C1.36458 10.1146 1.39583 10.3333 1.4375 10.5625C1.47917 10.7917 1.52083 10.9792 1.5625 11.125C1.83333 11.7708 2.28125 12.2188 2.90625 12.4688C3.07292 12.5312 3.28125 12.5833 3.53125 12.625C3.80208 12.6667 4.11458 12.6979 4.46875 12.7188C4.84375 12.7396 5.13542 12.75 5.34375 12.75C5.57292 12.75 5.89583 12.75 6.3125 12.75C6.75 12.75 6.98958 12.75 7.03125 12.75C7.09375 12.75 7.32292 12.75 7.71875 12.75C8.13542 12.75 8.45833 12.75 8.6875 12.75C8.91667 12.75 9.20833 12.7396 9.5625 12.7188C9.9375 12.6979 10.25 12.6667 10.5 12.625C10.7708 12.5833 10.9896 12.5312 11.1562 12.4688C11.8021 12.1979 12.25 11.75 12.5 11.125Z" fill="white"/>
                                            </svg>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="https://www.linkedin.com/company/coin-bank-usa/" class="footer__socialLink mb-2 mb-md-3" target="_blank">
                                            <svg class="footer__socialLink__icon" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3.125 14H0.21875V4.65625H3.125V14ZM1.6875 3.375C1.22917 3.375 0.833333 3.20833 0.5 2.875C0.166667 2.54167 0 2.14583 0 1.6875C0 1.22917 0.166667 0.833333 0.5 0.5C0.833333 0.166667 1.22917 0 1.6875 0C1.97917 0 2.25 0.0729167 2.5 0.21875C2.77083 0.364583 2.97917 0.572917 3.125 0.84375C3.29167 1.09375 3.375 1.375 3.375 1.6875C3.375 1.97917 3.29167 2.26042 3.125 2.53125C2.97917 2.78125 2.77083 2.98958 2.5 3.15625C2.25 3.30208 1.97917 3.375 1.6875 3.375ZM14 14H11.0938V9.4375C11.0938 9.08333 11.0833 8.80208 11.0625 8.59375C11.0417 8.36458 10.9896 8.11458 10.9062 7.84375C10.8229 7.55208 10.6667 7.33333 10.4375 7.1875C10.2292 7.04167 9.94792 6.96875 9.59375 6.96875C8.90625 6.96875 8.4375 7.1875 8.1875 7.625C7.95833 8.0625 7.84375 8.64583 7.84375 9.375V14H4.9375V4.65625H7.75V5.9375H7.78125C7.98958 5.52083 8.33333 5.16667 8.8125 4.875C9.29167 4.5625 9.86458 4.40625 10.5312 4.40625C11.2396 4.40625 11.8229 4.52083 12.2812 4.75C12.7604 4.95833 13.1146 5.28125 13.3438 5.71875C13.5938 6.13542 13.7604 6.59375 13.8438 7.09375C13.9479 7.59375 14 8.1875 14 8.875V14Z" fill="white"/>
                                            </svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                </div>
                <!-- footer bottom -->
                <div class="pb-5">
                        <div class="d-flex justify-content-between flex-wrap">
                            <ul class="list-inline fs-13">
                                <li class="list-inline-item">
                                    <a href="" class="footer__menu__link">Contrato de usuario</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="" class="footer__menu__link">Acuerdo de uso de Billetera Digital</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="" class="footer__menu__link">Políticas de privacidad</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="" class="footer__menu__link">Políticas de cookies</a>
                                </li>
                            </ul>
                            <ul class="list-inline fs-13">
                                <li class="list-inline-item text-muted">
                                    AKB Fintech &copy; 2020
                                </li>
                            </ul>
                        </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
export default {
    props: ['lang', 'user'],
    data() {
        return {
        laravel_token: $('meta[name="csrf-token"]').attr("content"),
        email: '',
        }
    },

    methods: {
        getUrl(endpoint) {
            return window.location.origin + endpoint;
        }
    },
    mounted () {
        this.$Progress.finish()
        $('[data-toggle="tooltip"]').tooltip();
    },
    created () {
        //  [App.vue specific] When App.vue is first loaded start the progress bar
        this.$Progress.start()
        //  hook the progress bar to start before we move router-view
        this.$router.beforeEach((to, from, next) => {
        //  does the page we want to go to have a meta.progress object
        if (to.meta.progress !== undefined) {
            let meta = to.meta.progress
            // parse meta tags
            this.$Progress.parseMeta(meta)
        }
        //  start the progress bar
        this.$Progress.start()
            //  continue to next page
            next()
        })
        //  hook the progress bar to finish after we've finished moving router-view
        this.$router.afterEach((to, from) => {
            //  finish the progress bar
            this.$Progress.finish()
        })
    }
}
</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition-property: opacity;
  transition-duration: .25s;
}

.fade-enter-active {
  transition-delay: .25s;
}

.fade-enter, .fade-leave-active {
  opacity: 0
}
</style>