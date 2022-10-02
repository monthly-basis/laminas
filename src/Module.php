<?php
namespace MonthlyBasis\Laminas;

use MonthlyBasis\Laminas\Model\Db as LaminasDb;

class Module
{
    public function getServiceConfig()
    {
        return [
            'factories' => [
                LaminasDb\Table::class => function ($sm) {
                    return new LaminasDb\Table(
                        $sm->get('laminas')
                    );
                },
            ],
        ];
    }
}
