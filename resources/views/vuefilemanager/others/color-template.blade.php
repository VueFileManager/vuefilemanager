<style>

        {{-- Group options --}}
        .group:hover .group-hover-text-theme {color: {{ $settings->app_color }} !important;}


        {{-- Single option --}}
        .svg-color-theme {fill: {{ $settings->app_color }}}

        .svg-stroke-theme {stroke: {{ $settings->app_color }}}
        .hover-svg-stroke-theme:hover {stroke: {{ $settings->app_color }}}

        .hover-svg-stroke-theme:hover rect {stroke: {{ $settings->app_color }}}
        .hover-svg-stroke-theme:hover line {stroke: {{ $settings->app_color }}}

        .bg-theme {background: {{ $settings->app_color }}}
        .bg-theme-50 {background: {{ $settings->app_color }}05}
        .bg-theme-100 {background: {{ $settings->app_color }}10}
        .bg-theme-800 {background: {{ $settings->app_color }}80}
        .hover-bg-theme:hover {background: {{ $settings->app_color }}}
        .hover-bg-theme-100:hover {background: {{ $settings->app_color }}10 !important;}

        .text-theme {color: {{ $settings->app_color }}}
        .hover-text-theme:hover {color: {{ $settings->app_color }}}

        .shadow-theme {box-shadow: 0 7px 16px 2px {{ $settings->app_color }}40}

        .border-theme {border-color: {{ $settings->app_color }}}
        .border-top-theme {border-top-color: {{ $settings->app_color }} !important;}
        .border-left-theme {border-left-color: {{ $settings->app_color }} !important;}
        .hover-border-theme:hover {border-color: {{ $settings->app_color }}}

        .focus-within-border-theme:focus-within {border-color: {{ $settings->app_color }} !important;}

        .focus-border-theme:focus {border-color: {{ $settings->app_color }}}
        .focus-border-theme:focus[type='email'] {border-color: {{ $settings->app_color }}}
        .focus-border-theme:focus[type='text'] {border-color: {{ $settings->app_color }}}
        .focus-border-theme:focus[type='password'] {border-color: {{ $settings->app_color }}}
        .focus-border-theme:focus[type='number'] {border-color: {{ $settings->app_color }}}

        {{-- Global Active Class --}}
        .active.active-bg-theme-100 {background: {{ $settings->app_color }}10 !important;}
        .active.active-border-theme {border-color: {{ $settings->app_color }} !important;}
        .active .active-text-theme {color: {{ $settings->app_color }}}

        {{-- Emoji Picker --}}
        .active-menu.focus-border-theme {border-color: {{ $settings->app_color }} !important;}

        {{-- Menubar --}}
        .router-link-active.home .button-icon {background: {{ $settings->app_color }}10}

        {{-- Content Panel --}}
        .router-link-active .text-theme {color: {{ $settings->app_color }} !important}
        .router-link-active .text-theme svg {color: {{ $settings->app_color }} !important}
        .router-link-active.border-bottom-theme {border-bottom-color: {{ $settings->app_color }} !important;}

        .is-active .text-theme {color: {{ $settings->app_color }} !important}
        .is-active .text-theme svg {color: {{ $settings->app_color }} !important}

        .menu-list-item.link:hover {color: {{ $settings->app_color }}}
        .menu-list-item.link:hover .text-theme svg {color: {{ $settings->app_color }}}

        {{-- Select Input --}}
        .input-area.is-active {border-color: {{ $settings->app_color }} !important}

        {{-- ButtonBase --}}
        .button-base.theme-solid {background: {{ $settings->app_color }}}
        .button-base.theme {background: {{ $settings->app_color }}10}
        .button-base.theme {color: {{ $settings->app_color }}}
        .button-base.theme .content {color: {{ $settings->app_color }}}

        .button-base.theme polyline,
        .button-base.theme path {
            color: {{ $settings->app_color }}
        }

        .switch.active {background: {{ $settings->app_color }} !important;}


        {{-- Dragged borders --}}
        .file-item.is-dragenter {border-color: {{ $settings->app_color }} !important;}
        .folder-item.is-dragenter {border-color: {{ $settings->app_color }} !important;}
        .favourites.is-dragenter .menu-list {border-color: {{ $settings->app_color }} !important;}

    </style>