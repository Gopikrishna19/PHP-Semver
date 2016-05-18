<?php

error_reporting(0);

$bumpMethods = ['major', 'minor', 'patch'];

if (!(isset($argv[1]) && in_array($argv[1], $bumpMethods))) {

    echo "\n\033[0;31mError: Invalid version method\033[0m \n\n" .
        "Usage: php semver.php <major|minor|patch>\n";

    exit(1);
}

$filename = 'version.ini';
$defaultVersion = '0.0.0';
$bumpMethod = $argv[1];

if (file_exists($filename)) {
    $version = parse_ini_file($filename)['version'] ?: $defaultVersion;
} else {
    $version = $defaultVersion;
}

$version = explode('.', $version);
$bumpIndex = array_search($bumpMethod, $bumpMethods);

foreach ($version as $i => &$v) {
    if ($i === $bumpIndex) {
        $v += 1;
    } elseif ($i > $bumpIndex) {
        $v = 0;
    }
}

$version = join('.', $version);

echo "Bumped to version: $version\n";

file_put_contents($filename, "version=$version");