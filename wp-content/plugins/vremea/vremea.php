<?php

/*
 * Plugin Name:       Vremea, in timp real
 * Plugin URI:        https://digitalstack.ro/vremea
 * Description:       Afiseaza vremea in timp real
 * Version:           1.0.0
 * Author:            Alex Bordei
 * Author URI:        https://alexbordei.dev
 */

function vremea_astazi_func($atts)
{
    $locatie = '';

    if (isset($atts['locatie'])) {
        $locatie = ucfirst($atts['locatie']);
    } else {
        $publicIp = file_get_contents('https://api.ipify.org');

        $location = wp_remote_get("http://ip-api.com/json/$publicIp");

        if (is_wp_error($location)) {
            $locatie = "Calarasi";
        } else {
            $location_data = json_decode(wp_remote_retrieve_body($location), true);

            $locatie = $location_data['regionName'];

        }
    }

    $vremea = get_weather_temp($locatie);
    return "Vremea astazi la $locatie, este de $vremea";
}

add_shortcode('vremea_astazi', 'vremea_astazi_func');

function get_weather_temp($locatie)
{
    $locatie = strtolower($locatie);
    $api_key = API_WEATHER_KEY;
    $api_url = "https://api.openweathermap.org/data/2.5/weather?q=$locatie&appid=$api_key&units=metric";

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


