<?php

namespace Helper\Support;


class Gateway
{
    /**
     * @param $code
     *
     * @return array|bool|mixed|object
     */
    public function billByCode($code)
    {
        $bill = (new Service)->all('bills', [
            'query' => "code=$code",
            'sort_by' => 'created_at',
            'sort_order' => 'asc',
        ]);
        if ($bill['bills']) {
            return $bill['bills'][0];
        }
        return false;
    }

    /**
     * @return bool
     */
    public function resetCode()
    {
        if ($bill = $this->billByCode($postId = (new Dump)->lastPostIdFromDump())) {
            (new Service)->update('bills', $bill['id'], json_encode(['code' => null]));
        }
        return $postId;
    }
}