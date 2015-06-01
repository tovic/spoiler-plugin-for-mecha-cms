<?php


/**
 * Plugin Updater
 * --------------
 */

Route::accept($config->manager->slug . '/plugin/' . basename(__DIR__) . '/update', function() use($config, $speak) {
    if($request = Request::post()) {
        Guardian::checkToken($request['token']);
        unset($request['token']); // Remove token from request array
        $css = File::open(PLUGIN . DS . basename(__DIR__) . DS . 'shell' . DS . 'pigment' . DS . $request['skin'] . '.css')->read();
        File::write(Converter::detractShell($css . "\n\n" . trim($request['css'])))->saveTo(PLUGIN . DS . basename(__DIR__) . DS . 'shell' . DS . 'spoiler.css');
        File::serialize($request)->saveTo(PLUGIN . DS . basename(__DIR__) . DS . 'states' . DS . 'config.txt', 0600);
        Notify::success(Config::speak('notify_success_updated', $speak->plugin));
        Guardian::kick(dirname($config->url_current));
    }
});