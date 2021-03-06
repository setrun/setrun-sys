<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

namespace setrun\sys\repositories;

use setrun\sys\entities\manage\Domain;
use setrun\sys\interfaces\i18nInterface;
use setrun\sys\exceptions\NotFoundException;

/**
 * Class DomainRepository.
 */
class DomainRepository
{
    /**
    * @var i18nInterface
    */
    protected $i18n;

    public function __construct(i18nInterface $i18n)
    {
        $this->i18n = $i18n;
    }

    /**
     * Find a domain item.
     * @param $id
     * @return Domain
     */
    public function get($id): Domain
    {
        if (!$model = Domain::findOne($id)) {
            throw new NotFoundException($this->i18n->t('setrun/sys/domain', 'Domain is not found'));
        }
        return $model;
    }

    /**
     * Save a domain item.
     * @param Domain $model
     * @return void
     */
    public function save(Domain $model): void
    {
        if (!$model->save()) {
            throw new \RuntimeException($this->i18n->t('setrun/sys', 'Saving error'));
        }
    }

    /**
     * Remove a domain item.
     * @param Domain $model
     * @return void
     */
    public function remove(Domain $model): void
    {
        if (!$model->delete()) {
            throw new \RuntimeException($this->i18n->t('setrun/sys', 'Removing error'));
        }
    }
}