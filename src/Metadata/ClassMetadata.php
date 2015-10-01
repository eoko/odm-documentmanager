<?php

namespace Eoko\ODM\DocumentManager\Metadata;

class ClassMetadata
{
    /** @var */
    protected $className;

    /** @var */
    protected $classMetadata;

    /** @var */
    protected $documentMetadata;

    /** @var */
    protected $fieldsMetadata;

    /** @var array */
    protected $identifiers = [];

    /** @var array */
    protected $indexes = [];

    /** @var array */
    protected $indexed = [];

    /** @var DriverInterface */
    protected $driver;

    public function __construct($className, $driver)
    {
        $this->className = $className;
        $this->driver = $driver;
    }

    /**
     * Gets the fully-qualified class name of this persistent class.
     * @return string
     */
    public function getName()
    {
        return $this->className;
    }

    /**
     * Gets the ReflectionClass instance for this mapped class.
     * @return \ReflectionClass
     */
    public function getClass()
    {
        return $this->driver->getClassMetadata($this->className);
    }

    /**
     * @return DocumentInterface
     * @throws \Exception
     */
    public function getDocument()
    {
        if (!isset($this->documentMetadata)) {
            $classMetadatas = $this->getClass();
            foreach ($classMetadatas as $classMetadataList) {
                foreach ($classMetadataList as $classMetadata) {
                    if ($classMetadata instanceof DocumentInterface) {
                        $this->documentMetadata = $classMetadata;
                    }
                }
            }
        }

        if (empty($this->documentMetadata)) {
            throw new \Exception('No document !!');
        }
        return $this->documentMetadata;
    }

    /**
     * Get all fields
     * @return array
     */
    public function getFields()
    {
        return $this->driver->getFieldsMetadata($this->className);
    }

    /**
     * Gets the mapped identifier field name.
     * The returned structure is an array of the identifier field names.
     * @return array
     */
    public function getIdentifier()
    {
        if (!$this->identifiers) {
            foreach ($this->getClass() as $items) {
                foreach ($items as $item) {
                    if ($item instanceof IdentifierInterface) {
                        foreach ($item->getIdentifier() as $name => $fields) {
                            $this->identifiers[$name] = ['key' => $fields, 'type' => $this->getTypeOfField($name), 'name' => $name];
                        }
                    }
                }
            }
        }
        return $this->identifiers;
    }

    protected function buildHash(array $keys)
    {
        sort($keys);
        return implode('---', $keys);
    }

    public function isIndexed($fieldname)
    {
        if (empty($this->indexed)) {
            foreach ($this->getClass() as $items) {
                foreach ($items as $key => $item) {
                    if ($item instanceof IndexInterface) {
                        foreach ($item->getFields() as $key => $field) {
                            $this->indexed[$key] = true;
                        }
                    }
                }
            }
        }
        return array_key_exists($fieldname, $this->indexed) ? true : false;
    }

    public function hasIndex(array $values)
    {
        $keys = array_intersect($this->getIdentifierFieldNames(), array_keys($values));
        return $this->isIndex($keys);
    }

    public function isIndex(array $keys)
    {
        return isset($this->getIndexes()[$this->buildHash($keys)]) ? true : false;
    }

    /**
     * @return IndexInterface[]
     */
    public function getIndexes()
    {
        if (!$this->indexes) {
            foreach ($this->getClass() as $items) {
                foreach ($items as $item) {
                    if ($item instanceof IndexInterface) {
                        $this->indexes[$this->buildHash(array_keys($item->getFields()))] = ['name' => $item->getName(), 'fields' => $item->getFields()];
                    }
                }
            }

            $fields = array_map(function ($field) {
                return $field['key'];
            }, $this->getIdentifier());

            $this->indexes[$this->buildHash($this->getIdentifierFieldNames())] = ['name' => 'primary', 'fields' => $fields];
        }
        return $this->indexes;
    }

    /**
     * Checks if the given field name is a mapped identifier for this class.
     * @param string $fieldName
     * @return boolean
     */
    public function isIdentifier($fieldName)
    {
        return array_key_exists($fieldName, $this->getIdentifier()) ? true : false;
    }

    /**
     * Returns an array of identifier field names numerically indexed.
     * @return array
     */
    public function getIdentifierFieldNames()
    {
        return array_keys($this->getIdentifier());
    }

    /**
     * Checks if the given field is a mapped property for this class.
     * @param string $fieldName
     * @return boolean
     */
    public function hasField($fieldName)
    {
        return in_array($fieldName, $this->getFieldNames()) ? true : false;
    }

    /**
     * A numerically indexed list of field names of this persistent class.
     * This array includes identifier fields if present on this class.
     *
     * @return array
     */
    public function getFieldNames()
    {
        return array_keys($this->getFields());
    }

    /**
     * Returns a type name of this field.
     *
     * This type names can be implementation specific but should at least include the php types:
     * integer, string, boolean, float/double, datetime.
     *
     * @param string $fieldName
     * @return string
     */
    public function getTypeOfField($fieldName)
    {
        $fields = $this->getFields();
        if (isset($fields[$fieldName])) {
            foreach ($fields[$fieldName] as $field) {
                if ($field instanceof FieldInterface) {
                    return $field->getType();
                }
            }
        }

        return;
    }
}
