<?php
// Plugin Name: GSS API login call
// Plugin URI: https://www.gsslogistics.nl/
// Description: API caller login TMS-ForYou
// Author: Brigitte

function enqueue_my_scripts() {
    wp_enqueue_script('jquery');
}

function loginForm( $atts ) {
    $error = ( get_query_var( 'error' ) ) ? get_query_var( 'error' ) : false;

    $dashboard    = 'gss'; // Default dashboard value
    $errorMessage = '';

    if ( $error == 'ok' ) {
        $errorMessage = '<div class="error">Gegevens onjuist, probeer het opnieuw.</div>';
    } elseif ( $error == 'gebruikersnaam' || $error == 'incorrect' ) {
        $errorMessage = '<div class="error">Gebruikersnaam of wachtwoord niet volledig of incorrect.</div>';
    }

    $content = <<<HTML
    <div class="loginform">
        $errorMessage
        
        <div class="error voorwaarden">
            Vergeet niet akkoord te gaan met de voorwaarden.
        </div>
        
        <form 
            id="loginformulier" 
            method="post" 
            action="https://webservices.tms-foryou.nl/webservices/loginkey?dashboard={$dashboard}">
            
            <input 
                type="text" 
                placeholder="Gebruikersnaam" 
                name="gebruikersnaam" />

            <input 
                type="password" 
                placeholder="Wachtwoord" 
                name="wachtwoord" />

            <input 
                type="checkbox" 
                id="voorwaarden"> 
            
            <label for="voorwaarden">
                Ik ga akkoord met de 
                <a 
                    target="_blank" 
                    href="https://globalsmartshipping.nl/algemene-voorwaarden/">
                    voorwaarden
                </a> 
                en 
                <a 
                    target="_blank" 
                    href="https://globalsmartshipping.nl/privacy-statement/">
                        privacyverklaring
                </a>
            </label>
            <input 
                onclick="inloggenStart(event)" 
                type="submit" 
                value="Inloggen" />

            <a 
                href="https://www.rijologistics.nl/wachtwoord-vergeten/" 
                class="wwlinkje">
                Wachtwoord vergeten? Klik hier
            </a>

        </form>
    </div>

    <script>
    function inloggenStart(e) {
        e.preventDefault();
        if (jQuery("#voorwaarden").is(":checked")) {
            jQuery("#loginformulier").submit();
        } else {
            jQuery(".foutmelding.voorwaarden").fadeIn();
        }
    }
    </script>
    HTML;

    add_action('wp_enqueue_scripts', 'enqueue_my_scripts');

    return $content;
}

add_shortcode( 'loginformulier', 'loginForm' );

function forgottenPassword( $atts ) {
	$error     = ( get_query_var( 'error' ) ) ? get_query_var( 'error' ) : false;
	$dashboard = 'gss'; // Default dashboard value

	$errorMessage = '';
	if ( $error == 'ok' ) {
		$errorMessage = '<div class="error groen">We hebben een e-mail verstuurd met uw gebruikersnaam en wachtwoord.</div>';
	} elseif ( $error == 'fout' ) {
		$errorMessage = '<div class="error">We konden dit e-mailadres niet terugvinden in onze gegevens.</div>';
	}

	$content = <<<HTML
        <div class="loginform">
            $errorMessage
            <span>
                Vul hieronder uw e-mailadres in. U ontvangt een e-mail met een link om een nieuw wachtwoord in te stellen.
            </span>

            <form  
                method="post" 
                action="https://webservices.tms-foryou.nl/webservices/loginkey?dashboard={$dashboard}&password=mail">
                <input type="text" placeholder="E-mailadres" name="emailadres" />
                <input type="submit" value="Wachtwoord opvragen" />
            </form>
        </div>
    HTML;

	return $content;
}

add_shortcode( 'wachtwoordvergeten', 'forgottenPassword' );
