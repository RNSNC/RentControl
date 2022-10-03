<?php

namespace App\Model\AmoCRM;

use AmoCRM\Filters\LeadsFilter;

class ShiftLeadsFilter extends LeadsFilter
{
    private string|null $with = null;

    public function getWith(): ?string
    {
        return $this->with;
    }

    public function setWith(?string $with): self
    {
        if (!empty($with)){
            $this->with = $with;
        }

        return $this;
    }

    public function buildFilter(): array
    {
        $filter = [];

        if (!is_null($this->getIds())) {
            $filter['filter']['id'] = $this->getIds();
        }

        if (!is_null($this->getNames())) {
            $filter['filter']['name'] = $this->getNames();
        }

        if (!is_null($this->getPrice())) {
            $filter['filter']['price'] = $this->getPrice();
        }

        if (!is_null($this->getCreatedBy())) {
            $filter['filter']['created_by'] = $this->getCreatedBy();
        }

        if (!is_null($this->getUpdatedBy())) {
            $filter['filter']['updated_by'] = $this->getUpdatedBy();
        }

        if (!is_null($this->getResponsibleUserId())) {
            $filter['filter']['responsible_user_id'] = $this->getResponsibleUserId();
        }

        if (!is_null($this->getCreatedAt())) {
            $filter['filter']['created_at'] = $this->getCreatedAt();
        }

        if (!is_null($this->getUpdatedAt())) {
            $filter['filter']['updated_at'] = $this->getUpdatedAt();
        }

        if (!is_null($this->getClosedAt())) {
            $filter['filter']['closed_at'] = $this->getClosedAt();
        }

        if (!is_null($this->getClosestTaskAt())) {
            $filter['filter']['closest_task_at'] = $this->getClosestTaskAt();
        }

        if (!is_null($this->getCustomFieldsValues())) {
            $filter['filter']['custom_fields_values'] = $this->getCustomFieldsValues();
        }

        if (!is_null($this->getStatuses())) {
            $filter['filter']['statuses'] = $this->getStatuses();
        }

        if (!is_null($this->getPipelineIds())) {
            $filter['filter']['pipeline_id'] = $this->getPipelineIds();
        }

        if (!is_null($this->getQuery())) {
            $filter['query'] = $this->getQuery();
        }

        if (!is_null($this->getWith())) {
            $filter['with'] = $this->getWith();
        }

        if (!is_null($this->getOrder())) {
            $filter['order'] = $this->getOrder();
        }

        return $this->buildPagesFilter($filter);
    }
}