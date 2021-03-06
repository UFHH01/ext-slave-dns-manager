<?php
// Copyright 1999-2017. Parallels IP Holdings GmbH.

namespace Modules_SlaveDnsManager_ApiRpc\DnsSlave;

use Modules_SlaveDnsManager_ApiRpc\AbstractCommand;
use Modules_SlaveDnsManager_Rndc;
use Modules_SlaveDnsManager_Slave;

class ListCommand extends AbstractCommand
{

    protected function _run()
    {
        $data = [];
        $rndc = new Modules_SlaveDnsManager_Rndc();

        foreach (Modules_SlaveDnsManager_Slave::getList() as $slave) {
            try {
                $details = $rndc->checkStatus($slave);
                $status = 'ok';
            } catch (\Exception $e) {
                $details = $e->getMessage();
                $status = 'warning';
            }
            $ip = (string)$slave->getIp();
            $config = (string)$slave->getConfig();

            $data[] = [
                'ip' => $ip,
                'status' => $status,
                'details' => $details,
                'config' => $config,
            ];
        }

        return ['slave' => $data];
    }
}

