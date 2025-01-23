
<div class="breadcrumb-area border-top">
    <div class="container custom-container-one">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-contents">
                    <h4 class="breadcrumb-contents-title"> {{ $title }} </h4>
                    <ul class="breadcrumb-contents-list list-style-none">
                        <li class="breadcrumb-contents-list-item"> <a href="{{ route('homepage') }}" class="breadcrumb-contents-list-item-link"> {{ __('Home') }}  </a> </li>
                        <li class="breadcrumb-contents-list-item"> {{ $innerTitle }} </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
