01-General
==========

Introduction
------------

The document manager permit the interaction with non relational data piloted by metadata and backed with help
of a driver to a NOSQL database  for example. The global library is quite simple and is heavily inspired from
existent ORM and ODM.

Global Structure
----------------

The document manager is composed by a backend driver and a metadata driver. It is fully-tested with a DynamoDB
Driver and an Annotation Driver that can be found in suggest. The first goal of Document Manager is to create 
database repositories. A document repository can be see as interface to the backend. It provide method to operate
on the database structure and datas.

    +-----------------------+
    |          ODM          +------>| Entity Repository A.
    |                       +------>| Entity Repository B.
    +-----------------------+
    |        Metadata       |<------| Annotation
    +-----------------------+
    |     Backend driver    |<----->| DynamoDB
    +-----------------------+

This is a simple example of what you can do with a repository :

    ```PHP
    
    $documentManager = $serviceLocator->get('Eoko\ODM\DocumentManager');
    $repository = $documentManager->getRepository('Eoko\Sample\Entity');
    $repository->createTable();
    
    ```


The Document Manager
--------------------
    
The repository
--------------

