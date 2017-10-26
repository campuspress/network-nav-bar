<?php

$options          = get_option( 'nnb_options' );
// $selectors        = $options['sticky_nav_selectors']['value'];
$translate        = $options['google_translate']['value'];
$color            = $options['text_color']['value'];
$background_color = $options['background_color']['value'];

/*
Only include necessary spaces as `$this->minify_html()`
does not remove spaces in order to prevent property values from
breaking and avoids the need for a larger utility to be used.
*/

$html = "<style type='text/css'>";
$html .= "
    body{
        margin-top:60px;
    }
    .network-nav-bar{
        position:fixed;
        top:0;
        left:0;
        right:0;
        z-index:99998;
        height:60px;
        background-color:{$background_color};
    }
    .admin-bar .network-nav-bar{
        top:32px;
    }
    @media screen and (max-width: 782px){
        .admin-bar .network-nav-bar{
            top:46px;
        }
    }
    #network-nav-bar *{
        color:{$color};
        font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';
    }
    #network-nav-bar p{
        margin:0;
        padding:0;
    }
    .nnb-brand{
        margin-left:0;
        margin-right:auto;
    }
    #network-nav-bar .nnb-logo-wrap img{
        max-height:50px;
        height:auto;
        max-width:100%;
    }
    #network-nav-bar .nnb-title{
        line-height:60px;
        font-size:20px;
        font-weight:500;
    }
    .nnb-brand-part{
        display:inline-block;
        vertical-align:middle;
    }
    .nnb-brand-part + .nnb-brand-part{
        margin-left:15px;
    }
    .nnb-container{
        display:flex;
        justify-content:flex-end;
        padding-left:15px;
        padding-right:15px;
    }
    .nnb-column{
        position:relative;
    }
    .nnb-menu{
        display:flex;
        list-style-type:none;
        margin:0;
        padding:0;
        z-index:1;
    }
    #network-nav-bar .nnb-menu a{
        font-size: 14px;
        font-weight: 400;
        line-height:60px;
        text-align:center;
        display:block;
        padding-left:12px;
        padding-right:12px;
        z-index:1;
    }
    #network-nav-bar .nnb-menu a:hover,
    #network-nav-bar .nnb-menu a:focus{
        text-decoration:none;
    }
    #network-nav-bar .nnb-menu a:focus{
        outline:thin dotted;
    }
";

// Google Translate widget custom styles
if ( 0 != $translate ) {
    $html .= "
        #network-nav-bar .nnb-trigger-translation{
            height:60px;
            padding:0 12px;
            background-color:transparent;
            background-image:none;
            box-shadow:none;
            text-shadow:none;
            border:0;
            font-weight:400;
            text-transform:none;
            border-radius:0;
            font-size:14px;
            font-weight: 400;
        }
        #network-nav-bar .nnb-trigger-translation:hover,
        #network-nav-bar .nnb-trigger-translation:focus{
            text-decoration:none;
        }
        #network-nav-bar .nnb-trigger-translation:focus{
            outline:thin dotted;
        }
        .nnb-translation-box{
            display:none;
            position:absolute;
            right:0;
            top:0;
            padding:0;
            width:200px;
            text-align:left;
            word-wrap:normal;
        }
        .nnb-translation-box a{
            display:inline!important;
            margin:0 4px!important;
            padding:0!important;
        }
        #network-nav-bar .nnb-translation-box .goog-te-gadget-simple .goog-te-menu-value span{
            color:#333;
        }
        #network-nav-bar .nnb-translation-box .goog-te-gadget-simple{
            border:none!important;
        }
        #network-nav-bar .nnb-translation-box #google_translate_element{
            clip:rect(1px,1px,1px,1px);
            clip-path:polygon(0 0,0 0,0 0,0 0);
            position:absolute!important;
            white-space:nowrap;
            height:1px;
            width:1px;
            overflow:hidden;
        }
        .goog-te-menu-frame{
            top:60px!important;
            left:0!important;
            right:0!important;
            margin:0 auto;
        }
        .admin-bar .goog-te-menu-frame{
            margin-top:32px;
        }
        @media screen and (max-width: 782px){
            .admin-bar .goog-te-menu-frame{
                margin-top:46px;
            }
        }
    ";
}

// Social navigation.
$html .= '
    #network-nav-bar .nnb-social-navigation a{
        position:relative;
        width:30px;
        height:60px;
        box-sizing:content-box;
        padding-left:4px;
        padding-right:4px;
    }
    #network-nav-bar .nnb-social-navigation a:before{
        width:100%;
        height:100%;
        -moz-osx-font-smoothing:grayscale;
        -webkit-font-smoothing:antialiased;
        display:inline-block;
        font-family:"Genericons";
        font-size:16px;
        font-style:normal;
        font-weight:normal;
        font-variant:normal;
        line-height:60px;
        speak: none;
        text-align:center;
        text-decoration:inherit;
        text-transform:none;
        vertical-align:top;
        content:"\f415";
        font-size:24px;
        position:absolute;
        top:0;
        left:0;
    }
    #network-nav-bar .nnb-social-navigation a[href*="codepen.io"]:before{
        content:"\f216";
    }
    #network-nav-bar .nnb-social-navigation a[href*="digg.com"]:before{
        content:"\f221";
    }
    #network-nav-bar .nnb-social-navigation a[href*="dribbble.com"]:before{
        content:"\f201";
    }
    #network-nav-bar .nnb-social-navigation a[href*="dropbox.com"]:before{
        content:"\f225";
    }
    #network-nav-bar .nnb-social-navigation a[href*="facebook.com"]:before{
        content:"\f203";
    }
    #network-nav-bar .nnb-social-navigation a[href*="flickr.com"]:before{
        content:"\f211";
    }
    #network-nav-bar .nnb-social-navigation a[href*="foursquare.com"]:before{
        content:"\f226";
    }
    #network-nav-bar .nnb-social-navigation a[href*="plus.google.com"]:before{
        content:"\f206";
    }
    #network-nav-bar .nnb-social-navigation a[href*="github.com"]:before{
        content:"\f200";
    }
    #network-nav-bar .nnb-social-navigation a[href*="instagram.com"]:before{
        content:"\f215";
    }
    #network-nav-bar .nnb-social-navigation a[href*="linkedin.com"]:before{
        content:"\f208";
    }
    #network-nav-bar .nnb-social-navigation a[href*="pinterest.com"]:before{
        content:"\f210";
    }
    #network-nav-bar .nnb-social-navigation a[href*="getpocket.com"]:before{
        content:"\f224";
    }
    #network-nav-bar .nnb-social-navigation a[href*="polldaddy.com"]:before{
        content:"\f217";
    }
    #network-nav-bar .nnb-social-navigation a[href*="reddit.com"]:before{
        content:"\f222";
    }
    #network-nav-bar .nnb-social-navigation a[href*="stumbleupon.com"]:before{
        content:"\f223";
    }
    #network-nav-bar .nnb-social-navigation a[href*="tumblr.com"]:before{
        content:"\f214";
    }
    #network-nav-bar .nnb-social-navigation a[href*="twitter.com"]:before{
        content:"\f202";
    }
    #network-nav-bar .nnb-social-navigation a[href*="vimeo.com"]:before{
        content:"\f212";
    }
    #network-nav-bar .nnb-social-navigation a[href*="wordpress.com"]:before,
    #network-nav-bar .nnb-social-navigation a[href*="wordpress.org"]:before{
        content:"\f205";
    }
    #network-nav-bar .nnb-social-navigation a[href*="youtube.com"]:before{
        content:"\f213";
    }
    #network-nav-bar .nnb-social-navigation a[href*="mailto:"]:before{
        content:"\f410";
    }
    #network-nav-bar .nnb-social-navigation a[href*="spotify.com"]:before{
        content:"\f515";
    }
    #network-nav-bar .nnb-social-navigation a[href*="twitch.tv"]:before{
        content:"\f516";
    }
    #network-nav-bar .nnb-social-navigation a[href$="/feed/"]:before{
        content:"\f413";
    }
    #network-nav-bar .nnb-social-navigation a[href*="path.com"]:before{
        content:"\f219";
    }
    #network-nav-bar .nnb-social-navigation a[href*="skype.com"]:before{
        content:"\f220";
    }
';

// Fixes elements that are sticky (fixed).
// if ( $selectors != '' ) {
// 	$html .= "
// 		{$selectors}{
// 			margin-top:60px!important
// 		}
// 		@media screen and (max-width: 782px){
// 			{$selectors}{
// 				margin-top:49px!important;
// 			}
// 		}
// 	";
// }

$html .= "</style>";

echo $this->minify_html( $html );
