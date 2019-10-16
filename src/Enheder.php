<?php

namespace MSML;

use Jsg\Odoo\Odoo;

/**
 * A class holding a collection of Enhed's.
 */
class Enheder
{
    protected $odooClient;
    protected $collection;

    /**
     * Construct using an Odoo Client.
     *
     * @param \Jsg\Odoo\Odoo $odooClient The Odoo Client to use for lookups.
     */
    public function __construct(Odoo $odooClient)
    {
        $this->odooClient = $odooClient;
        $this->collection = [];
    }

    /**
     * Get an Enhed by it's ID (i.e. "8960").
     *
     * @param string $enhedId The Enhed ID to lookup by (i.e. "8960").
     *
     * @return Enhed|null
     */
    public function getById(string $enhedId): ?Enhed
    {
        return $this->requestEnhed($enhedId, 'id');
    }

    /**
     * Get an Enhed by it's organization code (i.e. "2227-5").
     *
     * @param string $organizationCode The Enhed organization code to lookup by (i.e. "2227-5").
     *
     * @return Enhed|null
     */
    public function getByOrganizationCode(string $organizationCode): ?Enhed
    {
        return $this->requestEnhed($organizationCode, 'organization_code');
    }

    /**
     * Lookup an Enhed by it's ID (i.e. "2227-5") from Odoo.
     *
     * @param string $value The Enhed value to lookup by (i.e. "2227-5").
     * @param string $field The Enhed field to lookup in (i.e. "id" or "organisation_code").
     *
     * @return Enhed|null
     */
    protected function requestEnhed(string $value, string $field): ?Enhed
    {
        if (!empty($this->collection[$field][$value])) {
            return $this->collection[$field][$value];
        }

        $criteria = [[$field, '=', $value]];

        $organizationCode = $this->odooClient->search('member.organization', $criteria);

        if (empty($organizationCode)) {
            return null;
        }

        $this->collection[$field][$value] = new Enhed($this->odooClient, reset($organizationCode), $value);

        return $this->collection[$field][$value];
    }
}
