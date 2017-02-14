<?php

/**
 * paging function library
 * @package class/lib
 * @author  정현 <hjung01@simplexi.com>
 * @version 1.0
 * @since   2016.07.20
 */
class libPaging
{
    /**
     * The number of shown numbers
     * ex) << < 1 2 3 4 5 > >>  value is 5
     */
    const BLOCK_COUNT = 10;

    /**
     * instance
     * @var libPaging
     */
    protected static $oInstance;

    /**
     * get oInstance
     * @return libPaging
     */
    public static function getInstance()
    {
        if (self::$oInstance === null) {
            self::$oInstance = new self();
        }

        return self::$oInstance;
    }

    /**
     * get paging html, start record
     * @param array $aPagingParam paging param
     *                            'url' link url
     *                            'page_record' list row count
     *                            'now_page' current page number
     *                            'total_count' total row count
     *                            'search_data' search parameter
     *                            'page_type' paging type (front)
     * @return array array('sPaging' => '<div class....>', 'iStartRecord' => 3);
     */
    public function getPaging($aPagingParam)
    {
        // initialize parameter(type casting)
        $sUrl = $aPagingParam['url'];
        $iPageRecord = (int)$aPagingParam['page_record'];
        $iNowPage = (int)$aPagingParam['now_page'];
        $iTotalCount = (int)$aPagingParam['total_count'];
        $aSearchData = is_array($aPagingParam['search_data']) === true && count($aPagingParam['search_data']) > 0 ? $aPagingParam['search_data'] : array();
        $sPagingType = is_string($aPagingParam['page_type']) === true && strlen($aPagingParam['page_type']) > 0 ? $aPagingParam['page_type'] : null;

        // initialize paging number value
        $iTotalPage = (int)ceil($iTotalCount / $iPageRecord);
        $iTotalBlock = (int)ceil($iTotalPage / self::BLOCK_COUNT) - 1;
        $iNowBlock = (int)ceil($iNowPage / self::BLOCK_COUNT) - 1;
        $iStartRecord = (int)(($iNowPage - 1) * $iPageRecord);
        $iStartPage = (int)($iNowBlock * self::BLOCK_COUNT) + 1;
        $iEndPage = (int)(($iStartPage + self::BLOCK_COUNT) <= $iTotalPage) ? ($iStartPage + self::BLOCK_COUNT) - 1 : $iTotalPage;

        // get query string (exist search data)
        $sParam = $this->getParamData($aSearchData);

        // create paging html
        // prev button
        $sPrevHtml = '';
        // if ($iNowBlock <= $iTotalBlock && $iNowBlock !== 0) {
        if ($iNowPage === 1) {
            $sPrevHtml .= '';
        } else {
            $sPrevHtml .= '<a class="prev" href="' . $sUrl . '?page=' . ($iNowPage - 1) .  $sParam . '"><span>이전 10 페이지</span></a>';
        }
        // }

        // number list
        $aPageCount = array('start' => $iStartPage, 'end' => $iEndPage, 'now' => $iNowPage);
        $sListHtml = $this->_getPagingListHtml($aPageCount, $sUrl, $sParam);

        // next button
        $sNextHtml = '';
        // if ($iNowBlock < $iTotalBlock) {
        if ($iNowPage === $iTotalPage ) {
            $sNextHtml .= '';
        } else if ($iTotalCount === 0) {
            $sNextHtml .= '';
        } else {
            $sNextHtml .= '<a class="next" href="' . $sUrl . '?page=' . ($iNowPage + 1) . $sParam . '"><span>다음 10 페이지</span></a>';
        }
        // }
        // result paging html
        $aResult['sPaging'] = '<div class="mPaginate">' . $sPrevHtml . '<ol>' . $sListHtml . '</ol>' . $sNextHtml . '</div>';
        $aResult['iStartRecord'] = $iStartRecord;
        return $aResult;
    }

    /**
     * return list page html
     * @param array  $aPageCount array page info
     * @param string $sUrl       url
     * @param string $sParam     param string
     * @return string
     */
    private function _getPagingListHtml($aPageCount, $sUrl, $sParam)
    {
        $sListHtml = '';
        for ($i = $aPageCount['start']; $i <= $aPageCount['end']; $i++) {
            $sListHtml .= '<li>';
            if ($aPageCount['now'] === $i) {
                $sListHtml .= '<strong title="현재페이지">' . $i . '</strong>';
            } else {
                $sListHtml .= '<a href="' . $sUrl . '?page=' . $i . '' . $sParam . '" title="' . $i . ' 페이지로 이동" href="#none">' . $i . '</a>';
            }
            $sListHtml .= '</li>';
        }

        return $sListHtml;
    }

    /**
     * return param to query string type
     * @param array $aSearchData search info
     * @return string query string
     */
    public function getParamData($aSearchData)
    {
        // value initialize
        $sParam = '';
        // valid array data
        if (libValid::isArray($aSearchData) === false) {
            return '';
        }
        //Unset some values
        unset($aSearchData['page']);
        // unset($aSearchData['limit']);
        unset($aSearchData['total_count']);
        unset($aSearchData['mall_version']);

        foreach ($aSearchData as $sKey => $mValue) {
            $sParam = $sParam . $this->_setQueryString($mValue, $sKey);

        }
        return $sParam;
    }

    /**
     * set param array case
     * @param array  $mValue value
     * @param string $sKey   key
     * @param string $sParam result string
     * @return string
     */
    private function _setQueryString($mValue, $sKey)
    {
        if (libValid::isArray($mValue) === true) {
            foreach ($mValue as $mChildValue) {
                $sParam .= '&' . $sKey . '[]=' . $mChildValue;
            }
            return $sParam;
        }

        return '&' . $sKey . '=' . $mValue;
    }
}
