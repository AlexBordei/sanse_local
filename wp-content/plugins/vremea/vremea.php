<?php

/*
 * Plugin Name:       Vremea, in timp real
 * Plugin URI:        https://digitalstack.ro/vremea
 * Description:       Afiseaza vremea in timp real
 * Version:           1.0.0
 * Author:            Alex Bordei
 * Author URI:        https://alexbordei.dev
 */

function vremea_astazi_func( $atts ) {
    $locatie = "Bucharest";
    if(isset($atts['locatie'])) {
        $locatie = ucfirst($atts['locatie']);
    }

    $vremea = get_weather_temp($locatie);
    return "Vremea astazi la $locatie, este de $vremea";
}
add_shortcode( 'vremea_astazi', 'vremea_astazi_func' );

function get_weather_temp($locatie)
{
    $locatie = strtolower($locatie);
    $api_url = "https://api.openweathermap.org/data/2.5/weather?q=$locatie&appid=0b5109f774ac90587cf84d40b5858fd9&units=metric";

    // Face apelul HTTP GET
    $response = wp_remote_get($api_url);

    // Verifică dacă cererea a avut succes
    if (is_wp_error($response)) {
        return 'Eroare la cererea API';
    }

    // Decodează corpul răspunsului JSON
    $weather_data = json_decode(wp_remote_retrieve_body($response), true);

    // Verifică dacă datele sunt valide
    if (isset($weather_data['main']['temp'])) {
        $temperature = $weather_data['main']['temp'];

        return intval($temperature) . '°C';
    } else {
        // returneaza nimic
        return 'N/A';
    }
}