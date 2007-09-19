<?php

/*
+---------------------------------------------------------------------------+
| Openads v${RELEASE_MAJOR_MINOR}                                                              |
| ============                                                              |
|                                                                           |
| Copyright (c) 2003-2007 Openads Limited                                   |
| For contact details, see: http://www.openads.org/                         |
|                                                                           |
| This program is free software; you can redistribute it and/or modify      |
| it under the terms of the GNU General Public License as published by      |
| the Free Software Foundation; either version 2 of the License, or         |
| (at your option) any later version.                                       |
|                                                                           |
| This program is distributed in the hope that it will be useful,           |
| but WITHOUT ANY WARRANTY; without even the implied warranty of            |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
| GNU General Public License for more details.                              |
|                                                                           |
| You should have received a copy of the GNU General Public License         |
| along with this program; if not, write to the Free Software               |
| Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
+---------------------------------------------------------------------------+
$Id$
*/

class postscript_tables_core_999100
{
    var $oDBUpgrade;
    var $oDbh;

    function postscript_tables_core_999100()
    {

    }

    function execute_constructive($aParams)
    {
        $this->oDBUpgrade = $aParams[0];
        $this->oDbh = OA_DB::singleton(OA_DB::getDsn());
        $this->_log('**********postscript_tables_core_999100**********');
        $result = $this->insertData();
        $this->_logActual();
        return $result;
    }

    function execute_destructive($aParams)
    {
        return true;
    }

    function _log($msg)
    {
        $logOld = $this->oDBUpgrade->oLogger->logFile;
        $this->oDBUpgrade->oLogger->logFile = MAX_PATH.'/var/fantasy.log';
        $this->oDBUpgrade->oLogger->logOnly($msg);
        $this->oDBUpgrade->oLogger->logFile = $logOld;
        return true;
    }

    function _logActual()
    {
        $aExistingTables = $this->oDBUpgrade->_listTables();
        $prefix = $this->oDBUpgrade->prefix;
        if (!in_array($prefix.'bender', $aExistingTables))
        {
            $this->_log('changes_tables_core_999100:: TEST A : failed to create table '.$prefix.'bender');
        }
        else
        {
            $this->_log('changes_tables_core_999100::TEST A : created table '.$prefix.'bender defined as:');
            $aDef = $this->oDBUpgrade->_getDefinitionFromDatabase('bender');
            $this->_log(print_r($aDef['tables'],true));
        }
        if (!in_array($prefix.'astro', $aExistingTables))
        {
            $this->_log('changes_tables_core_999100:: TEST B : failed to create table '.$prefix.'astro defined as:');
        }
        else
        {
            $this->_log('changes_tables_core_999100::TEST B : created table '.$prefix.'astro defined as:');
            $aDef = $this->oDBUpgrade->_getDefinitionFromDatabase('astro');
            $this->_log(print_r($aDef['tables'],true));
            $query = 'SELECT COUNT(*) FROM '.$prefix.'astro';
            $result = $this->oDbh->queryOne($query);
            if (PEAR::isError($result))
            {
                $this->_log('postscript_tables_core_999100:: TEST C : failed to insert records in table astro');
            }
            $this->_log('postscript_tables_core_999100:: TEST C : inserted '.$result.' records in table astro');
        }
    }

    function insertData()
    {
        $table = $this->oDbh->quoteIdentifier($this->oDBUpgrade->prefix.'astro',true);
        for ($i=1;$i<11;$i++)
        {
            $query = "INSERT INTO
                      {$table}
                      (
                        id_field,
                        desc_field
                      )
                       VALUES
                      (
                        {$i},
                        'desc {$i}'
                      )";
            $result = $this->oDbh->exec($query);
            if (PEAR::isError($result))
            {
                $this->_log('postscript_tables_core_999100::insertData failed: '.$result->getUserInfo());
                return false;
            }
        }
        $this->_log('postscript_tables_core_999100::insertData complete');
        return true;
    }

}

?>