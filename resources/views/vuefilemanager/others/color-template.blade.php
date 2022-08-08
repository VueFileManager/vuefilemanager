<style>

        @php
            $color = $config->theme->color ?? '#00BC7E';
        @endphp

        {{-- Tailwind Helpers --}}
        .router-link-active .icon-active {
            color: {{ $color }};
        }
        .router-link-active .text-active {
            color: {{ $color }};
        }

        {{-- Group options --}}
        .group:hover:not(.hover-disabled) .group-hover-text-theme {color: {{ $color }} !important;}

        {{-- Single option --}}
        .svg-color-theme {
            fill: {{ $color }};
            stroke: {{ $color }};
        }
        .svg-color-theme-darken {
            fill: {{ $color }};
            stroke: {{ $color }};
            filter: brightness(0.80);
        }

        .svg-stroke-theme {stroke: {{ $color }}}
        .hover-svg-stroke-theme:hover {stroke: {{ $color }}}

        .hover-svg-stroke-theme:hover rect {stroke: {{ $color }}}
        .hover-svg-stroke-theme:hover line {stroke: {{ $color }}}

        .bg-theme {background: {{ $color }}}
        .bg-theme-50 {background: {{ $color }}05}
        .bg-theme-100 {background: {{ $color }}10}
        .bg-theme-200 {background: {{ $color }}20}
        .bg-theme-800 {background: {{ $color }}80}
        .hover-bg-theme:hover {background: {{ $color }}}
        .hover-bg-theme-100:hover {background: {{ $color }}10 !important;}

        .text-theme {color: {{ $color }}}
        .text-theme-darken {
            color: {{ $color }};
            filter: brightness(0.75);
        }

        .hover-text-theme:hover {color: {{ $color }}}

        .shadow-theme {box-shadow: 0 10px 15px -3px {{ $color }}40}

        .border-theme {border-color: {{ $color }}}
        .border-top-theme {border-top-color: {{ $color }} !important;}
        .border-left-theme {border-left-color: {{ $color }} !important;}
        .hover-border-theme:hover {border-color: {{ $color }} !important;}

        .focus-within-border-theme:focus-within {border-color: {{ $color }} !important;}

        .focus-border-theme:focus {border-color: {{ $color }} !important}
        .focus-border-theme:focus[type='email'] {border-color: {{ $color }}}
        .focus-border-theme:focus[type='text'] {border-color: {{ $color }}}
        .focus-border-theme:focus[type='password'] {border-color: {{ $color }}}
        .focus-border-theme:focus[type='number'] {border-color: {{ $color }}}

        {{-- Global Active Class --}}
        .active.active-bg-theme-100 {background: {{ $color }}10 !important;}
        .active.active-border-theme {border-color: {{ $color }} !important;}
        .active .active-text-theme {color: {{ $color }}}

        {{-- Emoji Picker --}}
        .active-menu.focus-border-theme {border-color: {{ $color }} !important;}

        {{-- Sidebar Navigation --}}
        .router-link-active.home .button-icon {background: {{ $color }}10}

        {{-- Content Panel --}}
        .router-link-active .text-theme {color: {{ $color }} !important}
        .router-link-active .text-theme svg {color: {{ $color }} !important}
        .router-link-active.border-bottom-theme {border-bottom-color: {{ $color }} !important;}

        .is-active .text-theme {color: {{ $color }} !important}
        .is-active .text-theme svg {color: {{ $color }} !important}

        .menu-list-item.link:hover {color: {{ $color }}}
        .menu-list-item.link:hover .text-theme svg {color: {{ $color }}}

        {{-- Select Input --}}
        .input-area.is-active {border-color: {{ $color }} !important}

        {{-- ButtonBase --}}
        .button-base.theme-solid {background: {{ $color }}}
        .button-base.theme-solid polyline,
        .button-base.theme-solid path {
            color: white;
        }


        .button-base.theme {background: {{ $color }}10}
        .button-base.theme {color: {{ $color }}}
        .button-base.theme .content {color: {{ $color }}}

        .button-base.theme polyline,
        .button-base.theme path {
            color: {{ $color }}
        }

        .switch.active {background: {{ $color }} !important;}


        {{-- Dragged borders --}}
        .border-theme {border-color: {{ $color }} !important;}
        .folder-item.is-dragenter {border-color: {{ $color }} !important;}
        .favourites.is-dragenter .menu-list {border-color: {{ $color }} !important;}

        {{-- Stripe --}}
        .StripeElement--focus {border-color: {{ $color }} !important;}

        {{-- Dark mode --}}
        .dark .dark-text-theme {color: {{ $color }} !important;}

        .dark .text-theme {
            color: {{ $color }}
        }

        {{-- File icon --}}
    </style>