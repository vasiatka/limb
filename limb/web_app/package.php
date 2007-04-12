<?php
/**
 * Limb Web Application Framework
 *
 * @link http://limb-project.com
 *
 * @copyright  Copyright &copy; 2004-2007 BIT
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 * @version    $Id: package.php 5647 2007-04-12 08:43:31Z pachanga $
 * @package    web_app
 */

require_once 'PEAR/PackageFileManager2.php';
require_once 'PEAR/PackageFileManager/Svn.php';

list($name, $baseVersion, $state) = explode('-', trim(file_get_contents(dirname(__FILE__) . '/VERSION')));
$changelog = htmlspecialchars(file_get_contents(dirname(__FILE__) . '/CHANGELOG'));
$summary = htmlspecialchars(file_get_contents(dirname(__FILE__) . '/SUMMARY'));
$description = htmlspecialchars(file_get_contents(dirname(__FILE__) . '/DESCRIPTION'));
$maintainers = explode("\n", trim(file_get_contents(dirname(__FILE__) . '/MAINTAINERS')));

$version = $baseVersion . (isset($argv[3]) ? $argv[3] : '');
$dir = dirname(__FILE__);

$apiVersion = $baseVersion;
$apiStability = $state;

$package = new PEAR_PackageFileManager2();

$result = $package->setOptions(array(
    'license'           => 'LGPL',
    'filelistgenerator' => 'file',
    'ignore'            => array('package.php',
                                 'package.xml',
                                 '*.tgz',
                                 'var',
                                 'setup.override.php',
                                 'common.ini.override'),
    //'simpleoutput'      => true,
    'baseinstalldir'    => 'limb/' . $name,
    'packagedirectory'  => './',
    'packagefile' => 'package.xml',
    'dir_roles' => array('docs' => 'doc',
                         'examples' => 'doc',
                         'tests' => 'test'),
    'roles' => array('*' => 'php'),
    ));
if(PEAR::isError($result))
{
  echo $result->getMessage();
  exit(1);
}

$package->setPackage($name);
$package->setSummary($summary);
$package->setDescription($description);

$package->setChannel('pear.limb-project.com');
$package->setAPIVersion($apiVersion);
$package->setReleaseVersion($version);
$package->setReleaseStability($state);
$package->setAPIStability($apiStability);
$package->setNotes($changelog);
$package->setPackageType('php');
$package->setLicense('LGPL', 'http://www.gnu.org/copyleft/lesser.txt');

foreach($maintainers as $line)
{
  list($role, $nick, $name, $email, $active) = explode(',', $line);
  $package->addMaintainer($role, $nick, $name, $email, $active);
}

$package->setPhpDep('5.1.4');
$package->setPearinstallerDep('1.4.99');

$package->addPackageDepWithChannel('required', 'core', 'pear.limb-project.com', '0.2.0');
$package->addPackageDepWithChannel('optional', 'active_record', 'pear.limb-project.com', '0.2.2');
$package->addPackageDepWithChannel('optional', 'cli', 'pear.limb-project.com', '0.2.0');
$package->addPackageDepWithChannel('required', 'config', 'pear.limb-project.com', '0.3.0');
$package->addPackageDepWithChannel('required', 'datetime', 'pear.limb-project.com', '0.1.0');
$package->addPackageDepWithChannel('optional', 'dbal', 'pear.limb-project.com', '0.2.2');
$package->addPackageDepWithChannel('required', 'log', 'pear.limb-project.com', '0.1.0');
$package->addPackageDepWithChannel('required', 'file_schema', 'pear.limb-project.com', '0.2.0');
$package->addPackageDepWithChannel('required', 'filter_chain', 'pear.limb-project.com', '0.1.1');
$package->addPackageDepWithChannel('required', 'i18n', 'pear.limb-project.com', '0.2.0');
$package->addPackageDepWithChannel('required', 'net', 'pear.limb-project.com', '0.1.3');
$package->addPackageDepWithChannel('required', 'session', 'pear.limb-project.com', '0.3.0');
$package->addPackageDepWithChannel('required', 'toolkit', 'pear.limb-project.com', '0.2.0');
$package->addPackageDepWithChannel('required', 'util', 'pear.limb-project.com', '0.1.0');
$package->addPackageDepWithChannel('required', 'validation', 'pear.limb-project.com', '0.3.0');
$package->addPackageDepWithChannel('required', 'view', 'pear.limb-project.com', '0.1.0');

$package->generateContents();

$result = $package->writePackageFile();

if(PEAR::isError($result))
{
  echo $result->getMessage();
  exit(1);
}
?>
