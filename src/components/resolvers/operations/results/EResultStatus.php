<?php
namespace deflou\components\resolvers\operations\results;

enum EResultStatus: string
{
    case Sucess = 'success';
    case Failed = 'failed';
}
