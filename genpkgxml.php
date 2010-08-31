<?php
/**
 * Generates and updates a package.xml file
 * dependencies : PEAR_PackageFileManager 1.6+
 * @author Laurent Bachelier <laurent@bachelier.name>
 */

error_reporting(E_ALL); // no E_STRICT
require_once('PEAR/PackageFileManager2.php');
PEAR::setErrorHandling(PEAR_ERROR_DIE);

$packagexml = new PEAR_PackageFileManager2;
$packagexml->setOptions(
array('baseinstalldir' => '/',
 'packagedirectory' => dirname(__FILE__),
 'filelistgenerator' => 'file',
 'ignore' => array('TODO'),
 'exceptions' => array('README' => 'doc', 'LICENSE' => 'doc'),
));

$packagexml->setPackage('amSimplePiePlugin');
$packagexml->setSummary('The purpose of this plugin is to facilitate the use of SimplePie within a Symfony project');
$packagexml->setDescription('The purpose of this plugin is to facilitate the use of SimplePie within a Symfony project: it allows you to autoload the SimplePie class and it uses the Symfony cache directory.');
$packagexml->setChannel('plugins.symfony-project.org');
$packagexml->addMaintainer('lead', 'laurentb', 'Laurent Bachelier', 'laurent@bachelier.name');
$packagexml->addMaintainer('lead', 'fabriceb', 'Fabrice Bernhard', 'fabriceb@allomatch.com');
$packagexml->setLicense('MIT License', 'http://www.symfony-project.org/license');

// This will ADD a changelog entry to an existing package.xml
$packagexml->setAPIVersion('1.0.0');
$packagexml->setReleaseVersion('1.2.1');
$packagexml->setNotes('Clément Herreman fixed an issue when calling amSP without a sfContext instance');

$packagexml->setReleaseStability('stable');
$packagexml->setAPIStability('stable');
$packagexml->addRelease();
$packagexml->setPackageType('php');
$packagexml->setPhpDep('5.2.1');
$packagexml->setPearinstallerDep('1.4.1');

// Supported versions of Symfony
$packagexml->addPackageDepWithChannel('required', 'symfony', 'pear.symfony-project.com', '1.0.0', '1.4.0');

$packagexml->generateContents(); // Add the files

if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make'))
  $packagexml->writePackageFile();
else
  $packagexml->debugPackageFile();

