# appserver.io build

## Introduction

This library provides generic ANT build- and deployment targets useful to build libraries for appserver.io.

The targets are mostly used for enabling continous integration with Travis-CI. The following targets are
provied out-of-the-box with a default configuration:

* clean (Deletes the directory with the generated artefacts)
* prepare (Prepares the directory to temporarily store generated artefacts)
* copy (Copies the sources to the temporary directory)
* deploy (Copies the sources to the deploy directory)
* apidoc (Generates the API documentation using PHPDocumentor)
* pdepend (Runs the PHPDepend tool and generats a graphs)
* phpcpd (Runs the copy and paste detection)
* phpcs (Runs the code sniffer and generates a report)
* phploc (Generate phploc.csv)
* phpmd (Runs the PHP Mess detector tool)
* build (Builds the library)
* run-tests (Runs the PHPUnit tests on Travis-CI and generates a report)

All artefacts that'll be generated during the one of the targets runtime will be stored in a temporarily
generated directory.

## Installation

If you want to install the generic build- and deployment targets to use with your library, you do this by add

```sh
{
    "require": {
        "appserver-io/build": "dev-master"
    },
}
```

to your ```composer.json``` and invoke ```composer update``` in your project.

## Usage

After installation you can import the XML file delivered with the library into your local ANT build file with:

```xml
<?xml version="1.0"?>
<!DOCTYPE project>
<project name="composer/package" basedir=".">
    
    <!-- initialize ENV variable -->
    <property environment="env" />
    
    <!-- initialize the library specific properties -->
    <property name="codepool" value="vendor"/>
    
    <!-- initialize the directory where we can find the real build files -->
    <property name="vendor.dir" value ="${basedir}/${codepool}" />
    <property name="build.dir" value="${vendor.dir}/appserver-io/build" />
    
    <!-- ==================================================================== -->
    <!-- Import the common build configuration file                           -->
    <!-- ==================================================================== -->
    <import file="${build.dir}/common.xml" optional="true"/>

</project>
```