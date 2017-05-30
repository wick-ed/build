<?php

/**
 * RoboFile.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product
 * @link      http://www.techdivision.com
 */

/**
 * Defines the available build tasks.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product
 * @link      http://www.techdivision.com
 *
 * @SuppressWarnings(PHPMD)
 */
class CommonRoboFile extends \Robo\Tasks
{

    /**
     * The build properties.
     *
     * @var array
     */
    protected $properties = array(
        'os.family' => 'linux',
        'base.dir' => __DIR__,
        'src.dir' => __DIR__ . '/src',
        'dist.dir' => __DIR__ . '/dist',
        'vendor.dir' => __DIR__ . '/vendor',
        'target.dir' => __DIR__ . '/target',
    );

    /**
     * CommonRoboFile constructor.
     */
    public function __construct()
    {
        $this->properties['phpdoc'] = array(
            'src.dir' => $this->properties['src.dir'],
            'target.dir' => $this->properties['target.dir'] . DIRECTORY_SEPARATOR . '${unique.name}' . DIRECTORY_SEPARATOR . 'apidoc',
            'ignore.dir' => 'vendor',
        );
        $this->properties['pdepend'] = array(
            'src.dir' => $this->properties['src.dir'],
            'ignore.dir' => 'vendor',
        );
        $this->properties['phpmd'] = array(
            'src.dir' => $this->properties['src.dir'],
            'exclude.dir' => 'vendor',
            'standard.file' => __DIR__ . '/phpmd.xml',
        );
        $this->properties['phpcpd'] = array(
            'src.dir' => $this->properties['src.dir'],
            'exclude.dir' => 'vendor',
            'additional.args' => '',
        );
        $this->properties['phpcs'] = array(
            'standard.file' => __DIR__ . '/phpcs.xml',
            'additional.args' => '',
        );
        $this->properties['phploc'] = array(
            'src.dir' => $this->properties['src.dir'],
            'exclude.dir' => 'vendor',
        );

        // load os family based properties
        switch ($this->properties['os.family']) {
            case 'win':
                $this->properties['dir.www'] = DIRECTORY_SEPARATOR . 'C:/Program Files';
                $this->properties['instance.base.dir'] = $this->properties['dir.www'] . DIRECTORY_SEPARATOR . 'appserver';
                $this->properties['appserver.bin.dir'] = $this->properties['instance.base.dir'] . DIRECTORY_SEPARATOR . 'php';
                break;
            case 'linux':
            case 'mac':
            default:
                $this->properties['dir.www'] = DIRECTORY_SEPARATOR . 'opt';
                $this->properties['instance.base.dir'] = $this->properties['dir.www'] . DIRECTORY_SEPARATOR . 'appserver';
                $this->properties['appserver.bin.dir'] = $this->properties['instance.base.dir'] . DIRECTORY_SEPARATOR . 'bin';
                break;
        }

        $this->properties['instance.base.dir'] =
        $this->properties['appserver.tmp.dir'] = $this->properties['instance.base.dir'] . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'tmp';
        $this->properties['appserver.deploy.dir'] = $this->properties['instance.base.dir'] . DIRECTORY_SEPARATOR . 'deploy';
        $this->properties['appserver.webapps.dir'] = $this->properties['instance.base.dir'] . DIRECTORY_SEPARATOR . 'webapps';
        $this->properties['deploy.dir'] = $this->properties['instance.base.dir'];
    }

    /**
     * Runs the composer install command.
     *
     * @return void
     */
    public function composerInstall()
    {
        // optimize autoloader with custom path
        $this->taskComposerInstall()
            ->preferDist()
            ->optimizeAutoloader()
            ->run();
    }

    /**
     * Runs the composer update command.
     *
     * @return void
     */
    public function composerUpdate()
    {
        // optimize autoloader with custom path
        $this->taskComposerUpdate()
            ->preferDist()
            ->optimizeAutoloader()
            ->run();
    }

    /**
     * Clean up the environment for a new build.
     *
     * @return void
     */
    public function clean()
    {
        $this->taskDeleteDir($this->properties['target.dir'])->run();
    }

    /**
     * Prepare's the environment for a new build.
     *
     * @return void
     */
    public function prepare()
    {
        $this->taskFileSystemStack()
            ->mkdir($this->properties['dist.dir'])
            ->mkdir($this->properties['target.dir'])
            ->mkdir(sprintf('%s/reports', $this->properties['target.dir']))
            ->run();
    }

    /**
     * Runs the PHPMD.
     *
     * @return void
     */
    public function runMd()
    {

        // run the mess detector
        $this->_exec(
            sprintf(
                '%s/bin/phpmd %s xml %s --reportfile %s/reports/pmd.xml --ignore-violations-on-exit',
                $this->properties['vendor.dir'],
                $this->properties['src.dir'],
                $this->properties['phpmd']['standard.file'],
                $this->properties['target.dir']
            )
        );
    }

    /**
     * Runs the PHPCPD.
     *
     * @return void
     */
    public function runCpd()
    {

        // run the copy past detector
        $this->_exec(
            sprintf(
                '%s/bin/phpcpd %s --log-pmd %s/reports/pmd-cpd.xml %s',
                $this->properties['vendor.dir'],
                $this->properties['src.dir'],
                $this->properties['target.dir'],
                $this->properties['phpcpd']['additional.args']
            )
        );
    }

    /**
     * Runs the PHPLOC.
     *
     * @return void
     */
    public function runLoc()
    {
        $this->_exec(
            sprintf(
                '%s/bin/phploc --log-xml %s/reports/pmd-cpd.xml --exclude %s %s',
                $this->properties['vendor.dir'],
                $this->properties['target.dir'],
                $this->properties['phploc']['exclude.dir'],
                $this->properties['src.dir']
            )
        );
    }

    /**
     * Runs the PHPCodeSniffer.
     *
     * @return void
     */
    public function runLint()
    {
        // run the code sniffer
        $this->_exec(
            sprintf(
                '%s/bin/phpcs -n --extensions=php --standard=%s --report-full --report-checkstyle=%s/reports/phpcs.xml %s %s',
                $this->properties['vendor.dir'],
                $this->properties['phpcs']['standard.file'],
                $this->properties['target.dir'],
                $this->properties['phpcs']['additional.args'],
                $this->properties['src.dir']
            )
        );
    }
    /*
<target name="phplint" description="Runs a PHP lint syntax check on the PHP source files.">
<apply executable="php" failonerror="true">
<arg value="-l" />
<fileset dir="${php-src.dir}">
                <include name="***.php" />
                <exclude name="vendor/**" />
            </fileset>
        </apply>
    </target>
    */
    /**
     * Runs the PHPCodeSniffer.
     *
     * @return void
     */
    public function runCs()
    {
        // run the code sniffer

    }

    /**
     * Runs the PHPUnit tests.
     *
     * @return void
     */
    public function runTests()
    {
        // run PHPUnit
        $this->taskPHPUnit(sprintf('%s/bin/phpunit', $this->properties['vendor.dir']))
            ->configFile('phpunit.xml')
            ->bootstrap('bootstrap.php')
            ->run();
    }

    /**
     * The complete build process.
     *
     * @return void
     */
    public function build()
    {
        $this->clean();
        $this->prepare();
        $this->runCs();
        $this->runCpd();
        $this->runMd();
        $this->runLoc();
        $this->runTests();

       // <antcall target="phplint" />
        //<antcall target="pdepend" />
    }
}
