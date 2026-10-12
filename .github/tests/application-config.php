<?php
/**
 * Checks config/application.php against several .env files. Run from the project root:
 * php .github/tests/application-config.php
 */

$root = dirname(__DIR__, 2);
$base = "WP_HOME=http://example.test\nDB_NAME=wp\nDB_USER=wp\nDB_PASSWORD=wp\n";
$cases = [
    'minimal .env' => [$base, ['WP_DEBUG' => false, 'DB_HOST' => 'localhost', 'DB_CHARSET' => 'utf8mb4', 'WP_SITEURL' => 'http://example.test/wp', 'WP_CONTENT_URL' => 'http://example.test/content', 'DISABLE_WP_CRON' => false]],
    'APP_DEBUG=1' => [$base . "APP_DEBUG=1\n", ['WP_DEBUG' => true]],
    'APP_DEBUG=true' => [$base . "APP_DEBUG=true\n", ['WP_DEBUG' => true]],
    'DISABLE_WP_CRON=false' => [$base . "DISABLE_WP_CRON=false\n", ['DISABLE_WP_CRON' => false]],
    'DISABLE_WP_CRON=true' => [$base . "DISABLE_WP_CRON=true\n", ['DISABLE_WP_CRON' => true]],
    'WP_POST_REVISIONS=false' => [$base . "WP_POST_REVISIONS=false\n", ['WP_POST_REVISIONS' => false]],
    'WP_POST_REVISIONS=5' => [$base . "WP_POST_REVISIONS=5\n", ['WP_POST_REVISIONS' => '5']],
    'empty DB_PASSWORD' => ["WP_HOME=http://example.test\nDB_NAME=wp\nDB_USER=wp\nDB_PASSWORD=\n", ['DB_PASSWORD' => '']],
    'WP_SITEURL from .env' => [$base . "WP_SITEURL=http://cms.example.test\n", ['WP_SITEURL' => 'http://cms.example.test']],
    'DB_PREFIX' => [$base . "DB_PREFIX=site_\n", ['table_prefix' => 'site_']],
];

$failures = 0;
foreach ($cases as $label => [$env, $expected]) {
    $dir = sys_get_temp_dir() . '/simply-config-' . md5($label);
    @mkdir($dir . '/config', 0777, true);
    copy($root . '/config/application.php', $dir . '/config/application.php');
    file_put_contents($dir . '/.env', $env);
    $script = sprintf(
        'set_error_handler(function ($l, $m) { echo "WARNING: $m\n"; return true; }); require %s; require %s; $c = []; foreach (%s as $k) { $c[$k] = $k === "table_prefix" ? $table_prefix : (defined($k) ? constant($k) : "UNDEFINED"); } echo json_encode($c);',
        var_export($root . '/vendor/autoload.php', true),
        var_export($dir . '/config/application.php', true),
        var_export(array_keys($expected), true)
    );
    $output = (string) shell_exec('php -r ' . escapeshellarg($script) . ' 2>&1');
    $actual = json_decode((string) substr($output, (int) strrpos($output, '{')), true);
    $ok = !str_contains($output, 'WARNING') && $actual === $expected;
    echo ($ok ? '[OK]   ' : '[FAIL] ') . $label . ($ok ? '' : ' => ' . trim($output)) . PHP_EOL;
    $failures += $ok ? 0 : 1;
}
exit($failures > 0 ? 1 : 0);
