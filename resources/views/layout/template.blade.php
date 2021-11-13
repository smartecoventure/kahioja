<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @if(isset($page->meta_tag) && isset($page->meta_description))
            <meta name="keywords" content="{{ $page->meta_tag }}">
            <meta name="description" content="{{ $page->meta_description }}">
            <title>{{$gs->title}}</title>
        @elseif(isset($blog->meta_tag) && isset($blog->meta_description))
            <meta name="keywords" content="{{ $blog->meta_tag }}">
            <meta name="description" content="{{ $blog->meta_description }}">
            <title>{{$gs->title}}</title>
        @elseif(isset($productt))
            <meta name="keywords" content="{{ !empty($productt->meta_tag) ? implode(',', $productt->meta_tag ): '' }}">
            <meta name="description" content="{{ $productt->meta_description != null ? $productt->meta_description : strip_tags($productt->description) }}">
            <meta property="og:title" content="{{$productt->name}}" />
            <meta property="og:description" content="{{ $productt->meta_description != null ? $productt->meta_description : strip_tags($productt->description) }}" />
            <meta property="og:image" content="{{asset('assets/images/thumbnails/'.$productt->thumbnail)}}" />
            <meta name="author" content="Kahioja Store">
            <title>{{substr($productt->name, 0,11)."-"}}{{$gs->title}}</title>
        @else
            <meta name="keywords" content="Kahioja">
            <meta name="author" content="Kahioja Store">
            <title>{{$gs->title}}</title>
        @endif
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Fonts -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
	    <link rel="icon"  type="image/x-icon" href="{{ asset('images/favicon.ico')}}"/>
        <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    
    </head>
    <body>
        <!-- Nav  -->
        <div id="app">
            <nav-component></nav-component>
            @if(!Auth::guard('web')->check())
                <script> window.authUser = null </script>    
            @else
                <script> window.authUser = {!! json_encode(Auth::user()); !!} </script>        
            @endif 
            @yield('main')
        </div>
        
        <!-- Footer  -->
        <div id="footer" class="mt-16 py-12">
            <div class="grid grid-cols-4 gap-6 px-14">
                <div class="col-span-3">
                    <div>
                        <svg width="197" height="43" viewBox="0 0 197 43" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M29.9105 40.487C32.1742 40.487 33.9928 38.6489 33.9928 36.4046C33.9928 34.1603 32.1548 32.3223 29.9105 32.3223C27.6468 32.3223 25.8281 34.1603 25.8281 36.4046C25.8281 38.6489 27.6468 40.487 29.9105 40.487Z" fill="white"/><path d="M11.8011 42.3835C14.0648 42.3835 15.8835 40.5454 15.8835 38.3011C15.8835 36.0374 14.0454 34.2188 11.8011 34.2188C9.55678 34.2188 7.71875 36.0568 7.71875 38.3011C7.71875 40.5454 9.55678 42.3835 11.8011 42.3835Z" fill="white"/><path d="M30.0082 30.987L9.22883 33.1733C5.08843 33.5989 1.39303 30.6 0.948035 26.479L0 17.521L35.7545 13.7676L36.7025 22.7255C37.1668 26.8659 34.1486 30.5613 30.0082 30.987Z" fill="white"/><path d="M5.30474 13.5548C4.64692 6.84119 9.59991 0.766028 16.3135 0.0695131C23.0272 -0.64635 29.1217 4.2486 29.8762 10.9816L25.6004 11.4459C25.1167 7.05401 21.1311 3.86165 16.7392 4.34534C12.3473 4.80968 9.11622 8.7566 9.54187 13.1485L5.30474 13.5548Z" fill="white"/><path d="M54.5427 22.7252L51.8147 25.7434V31.8766H47.5195V9.82031H51.8147V19.8037L60.6566 9.82031H66.2867L57.5416 19.4361L66.3448 31.8766H60.9468L54.5427 22.7252Z" fill="white"/><path d="M109.587 32.0895V22.9381H99.971V32.0895H95.6758V10.0332H99.971V18.8944H109.587V10.0332H113.921V32.0895H109.587Z" fill="white"/><path d="M119.59 32.0895V10.0332H123.924V32.0895H119.59Z" fill="white"/><path d="M139.864 32.9802C133.557 32.9802 128.43 27.8531 128.43 21.5458C128.43 15.2385 133.557 10.1113 139.864 10.1113C146.171 10.1113 151.299 15.2385 151.299 21.5458C151.299 27.8531 146.171 32.9802 139.864 32.9802ZM139.864 14.4839C135.956 14.4839 132.783 17.6569 132.783 21.5651C132.783 25.4734 135.956 28.6464 139.864 28.6464C143.772 28.6464 146.945 25.4734 146.945 21.5651C146.945 17.6376 143.772 14.4839 139.864 14.4839Z" fill="white"/><path d="M80.4501 9.41406C74.1427 9.41406 69.0156 14.5412 69.0156 20.8485V31.8767H73.3688V25.105H87.5313V32.0315H91.8845V20.8679C91.8845 14.5412 86.7574 9.41406 80.4501 9.41406ZM80.4501 13.7673C84.3196 13.7673 87.4539 16.9016 87.512 20.7324H73.3495C73.4269 16.8823 76.5806 13.7673 80.4501 13.7673Z" fill="white"/><path d="M185.563 9.41406C179.256 9.41406 174.129 14.5412 174.129 20.8485V31.8767H178.482V25.105H192.645V32.0315H196.998V20.8679C196.998 14.5412 191.871 9.41406 185.563 9.41406ZM185.563 13.7673C189.433 13.7673 192.567 16.9016 192.625 20.7324H178.463C178.56 16.8823 181.694 13.7673 185.563 13.7673Z" fill="white"/><path d="M170.511 24.5639V10.3047H166.139V24.5639C166.139 26.5954 164.475 28.24 162.463 28.24C160.431 28.24 158.787 26.5761 158.787 24.5639C158.787 24.4285 158.787 24.293 158.806 24.1576C158.806 24.0996 158.826 24.0222 158.845 23.9448H154.453C154.453 24.0028 154.453 24.0802 154.434 24.1576C154.434 24.293 154.434 24.4285 154.434 24.5639C154.434 28.9945 158.032 32.5932 162.463 32.5932C166.835 32.5932 170.415 29.0719 170.492 24.738L170.511 24.5639Z" fill="white"/></svg>
                    </div>
                    <div class="w-1/2 mt-12">
                        <p>
                            Kahioja is an ecommerce platform that serve local market across Africa. We aim to give access to myriad of products on our platform and help businesses grow whilst making sales and delivery as ease. 
                        </p>
                    </div>
                    <div class="mt-12">
                        <p>
                            Copyright &copy; 2021. All Rights Reserved By Kahioja
                        </p>
                    </div>
                </div>
                <div class="col-span-1">
                    <h1>Useful Links</h1>
                    <div class="mt-3">
                        <ul id="footer-nav">
                            <li class="py-4">
                                <a href="#">All Categories</a>
                            </li>
                            <li class="py-2">
                               <a href="">Track Orders</a>
                            </li>
                            <li class="py-2">
                                <a href="">Privacy &amp; Security</a>
                            </li>
                            <li class="py-2">
                               <a href="">Terms &amp; Policy</a>
                            </li>
                        </ul>
                    </div>
                    <div class="flex mt-3 justify-between w-1/3">
                        <span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.017 2H7.947C6.37015 2.00185 4.85844 2.62914 3.74353 3.74424C2.62862 4.85933 2.00159 6.37115 2 7.948L2 16.018C2.00185 17.5948 2.62914 19.1066 3.74424 20.2215C4.85933 21.3364 6.37115 21.9634 7.948 21.965H16.018C17.5948 21.9631 19.1066 21.3359 20.2215 20.2208C21.3364 19.1057 21.9634 17.5938 21.965 16.017V7.947C21.9631 6.37015 21.3359 4.85844 20.2208 3.74353C19.1057 2.62862 17.5938 2.00159 16.017 2V2ZM19.957 16.017C19.957 16.5344 19.8551 17.0468 19.6571 17.5248C19.4591 18.0028 19.1689 18.4371 18.803 18.803C18.4371 19.1689 18.0028 19.4591 17.5248 19.6571C17.0468 19.8551 16.5344 19.957 16.017 19.957H7.947C6.90222 19.9567 5.90032 19.5415 5.16165 18.8026C4.42297 18.0638 4.008 17.0618 4.008 16.017V7.947C4.00827 6.90222 4.42349 5.90032 5.16235 5.16165C5.90122 4.42297 6.90322 4.008 7.948 4.008H16.018C17.0628 4.00827 18.0647 4.42349 18.8034 5.16235C19.542 5.90122 19.957 6.90322 19.957 7.948V16.018V16.017Z" fill="white"/><path d="M11.9823 6.81836C10.6137 6.82048 9.30184 7.36514 8.33421 8.33297C7.36658 9.30079 6.82216 10.6128 6.82031 11.9814C6.8219 13.3503 7.36634 14.6627 8.33421 15.6308C9.30209 16.5988 10.6144 17.1435 11.9833 17.1454C13.3524 17.1438 14.665 16.5992 15.6331 15.6311C16.6012 14.663 17.1457 13.3505 17.1473 11.9814C17.1452 10.6124 16.6003 9.30024 15.632 8.33255C14.6637 7.36486 13.3512 6.82068 11.9823 6.81936V6.81836ZM11.9823 15.1374C11.1456 15.1374 10.3431 14.805 9.75139 14.2133C9.15971 13.6216 8.82731 12.8191 8.82731 11.9824C8.82731 11.1456 9.15971 10.3431 9.75139 9.75144C10.3431 9.15976 11.1456 8.82736 11.9823 8.82736C12.8191 8.82736 13.6216 9.15976 14.2132 9.75144C14.8049 10.3431 15.1373 11.1456 15.1373 11.9824C15.1373 12.8191 14.8049 13.6216 14.2132 14.2133C13.6216 14.805 12.8191 15.1374 11.9823 15.1374Z" fill="white"/><path d="M17.155 8.09509C17.8381 8.09509 18.392 7.54127 18.392 6.85809C18.392 6.17492 17.8381 5.62109 17.155 5.62109C16.4718 5.62109 15.918 6.17492 15.918 6.85809C15.918 7.54127 16.4718 8.09509 17.155 8.09509Z" fill="white"/></svg>
                        </span>
                        <span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.6955 8.937V10.314H9.6875V11.997H10.6955V17H12.7675V11.997H14.1575C14.1575 11.997 14.2885 11.19 14.3515 10.307H12.7755V9.157C12.7755 8.984 13.0015 8.753 13.2255 8.753H14.3535V7H12.8185C10.6445 7 10.6955 8.685 10.6955 8.937Z" fill="white"/><path d="M6 4C5.46957 4 4.96086 4.21071 4.58579 4.58579C4.21071 4.96086 4 5.46957 4 6V18C4 18.5304 4.21071 19.0391 4.58579 19.4142C4.96086 19.7893 5.46957 20 6 20H18C18.5304 20 19.0391 19.7893 19.4142 19.4142C19.7893 19.0391 20 18.5304 20 18V6C20 5.46957 19.7893 4.96086 19.4142 4.58579C19.0391 4.21071 18.5304 4 18 4H6ZM6 2H18C19.0609 2 20.0783 2.42143 20.8284 3.17157C21.5786 3.92172 22 4.93913 22 6V18C22 19.0609 21.5786 20.0783 20.8284 20.8284C20.0783 21.5786 19.0609 22 18 22H6C4.93913 22 3.92172 21.5786 3.17157 20.8284C2.42143 20.0783 2 19.0609 2 18V6C2 4.93913 2.42143 3.92172 3.17157 3.17157C3.92172 2.42143 4.93913 2 6 2V2Z" fill="white"/></svg>
                        </span>
                        <span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17 8.94701C16.632 9.10701 16.237 9.21701 15.822 9.26501C16.246 9.01501 16.57 8.61901 16.724 8.14801C16.326 8.37901 15.888 8.54801 15.42 8.63801C15.2269 8.43558 14.9945 8.27462 14.7372 8.16493C14.4798 8.05524 14.2028 7.99913 13.923 8.00001C12.79 8.00001 11.872 8.90501 11.872 10.02C11.872 10.178 11.89 10.332 11.925 10.48C11.1123 10.4414 10.3166 10.2339 9.58865 9.87056C8.86068 9.50726 8.21643 8.99621 7.697 8.37001C7.51456 8.67711 7.41851 9.02781 7.419 9.38501C7.419 10.085 7.782 10.705 8.332 11.066C8.00644 11.0558 7.68786 10.9691 7.402 10.813V10.838C7.40509 11.3075 7.5708 11.7613 7.87092 12.1224C8.17103 12.4834 8.587 12.7292 9.048 12.818C8.74536 12.898 8.42869 12.9096 8.121 12.852C8.25513 13.2559 8.51167 13.608 8.85502 13.8594C9.19837 14.1108 9.61148 14.2491 10.037 14.255C9.30696 14.8176 8.41064 15.1215 7.489 15.119C7.324 15.119 7.161 15.109 7 15.091C7.94047 15.6863 9.03096 16.0016 10.144 16C13.918 16 15.981 12.922 15.981 10.252L15.974 9.99001C16.3763 9.70729 16.7239 9.3539 17 8.94701Z" fill="white"/><path d="M6 4C5.46957 4 4.96086 4.21071 4.58579 4.58579C4.21071 4.96086 4 5.46957 4 6V18C4 18.5304 4.21071 19.0391 4.58579 19.4142C4.96086 19.7893 5.46957 20 6 20H18C18.5304 20 19.0391 19.7893 19.4142 19.4142C19.7893 19.0391 20 18.5304 20 18V6C20 5.46957 19.7893 4.96086 19.4142 4.58579C19.0391 4.21071 18.5304 4 18 4H6ZM6 2H18C19.0609 2 20.0783 2.42143 20.8284 3.17157C21.5786 3.92172 22 4.93913 22 6V18C22 19.0609 21.5786 20.0783 20.8284 20.8284C20.0783 21.5786 19.0609 22 18 22H6C4.93913 22 3.92172 21.5786 3.17157 20.8284C2.42143 20.0783 2 19.0609 2 18V6C2 4.93913 2.42143 3.92172 3.17157 3.17157C3.92172 2.42143 4.93913 2 6 2V2Z" fill="white"/></svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
