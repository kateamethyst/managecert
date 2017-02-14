<div class="headingArea">
    <div class="mTitle">
        <h1>접수처 관리</h1>
    </div>
</div>
<form id="search_form" action="[link=admin/reception/index]" method="GET">
<div class="section index">
    <div class="mBoard gSmall">
        <table border="1" class="eChkColor">
            <tr>
                <th scope="row">검색분류</th>
                <td colspan="2">
                    <select class="fSelect" name="classification">
                        <option value="certificate" <?php echo _('' . ($aArgs['classification'] === 'certificate') ? 'selected' : ''); ?> >자격증</option>
                        <option value="reception" <?php echo _('' . ($aArgs['classification'] === 'reception') ? 'selected' : ''); ?> >접수처</option>
                        <option value="test_location" <?php echo _('' . ($aArgs['classification'] === 'test_location') ? 'selected' : ''); ?> >시험장소</option>
                    </select>
                    <input type="text" name="classification_value" class="fText" value="<?php echo '' . ($aArgs['classification_value'] === null) ? '' : htmlspecialchars($aArgs['classification_value'], ENT_QUOTES, 'UTF-8'); ?>" style="width:280px;">
                </td>
            </tr>
            <tr>
                <th scope="row">등록일</th>
                <td colspan="2">
                    <a href="#none" class="btnDate eDateBtn btnSrhDate <?php echo '' . ($aArgs['selected_date'] === 'today') ? 'selected' : ''; ?>" data-name="today" data-from ="<?php echo date('Y-m-d'); ?>">
                        <span>오늘</span>
                    </a> 
                    <a href="#none" class="btnDate eDateBtn btnSrhDate <?php echo '' . ($aArgs['selected_date'] === 'three_days') ? 'selected' : ''; ?>" data-name="three_days" data-from ="<?php echo date('Y-m-d', strtotime(libConfig::THREE_DAYS)); ?>">
                        <span>3일</span>
                    </a> 
                    <a href="#none" class="btnDate eDateBtn btnSrhDate <?php echo '' . ($aArgs['selected_date'] === null || $aArgs['selected_date'] === 'seven_days') ? 'selected' : ''; ?>" data-name="seven_days" data-from ="<?php echo date('Y-m-d', strtotime(libConfig::SEVEN_DAYS)); ?>">
                        <span>7일</span>
                    </a> 
                    <a href="#none" class="btnDate eDateBtn btnSrhDate <?php echo '' . ($aArgs['selected_date'] === 'one_month') ? 'selected' : ''; ?>" data-name="one_month" data-from ="<?php echo date('Y-m-d', strtotime(libConfig::ONE_MONTH)); ?>">
                        <span>1개월</span>
                    </a>
                    <a href="#none" class="btnDate eDateBtn btnSrhDate <?php echo '' . ($aArgs['selected_date'] === 'three_months') ? 'selected' : ''; ?>" data-name="three_months" data-from ="<?php echo date('Y-m-d', strtotime(libConfig::THREE_MONTHS)); ?>">
                        <span>3개월</span>
                    </a>
                    <a href="#none" class="btnDate eDateBtn btnSrhDate <?php echo '' . ($aArgs['selected_date'] === 'one_year') ? 'selected' : ''; ?>" data-name="one_year" data-from ="<?php echo date('Y-m-d', strtotime(libConfig::ONE_YEAR . ' ' . libConfig::ONE_DAY)); ?>">
                        <span>1년</span>
                    </a>
                    <a href="#none" id="allDate" class="btnDate eDateBtn btnSrhDate <?php echo ($aArgs['start_date'] === '전체' ? 'selected' : ''); ?>" data-from ="전체">
                        <span>전체</span>
                    </a>

                    <input type="hidden" name="selected_date" value="<?php echo  '' . (isset($aArgs['selected_date']) === true) ? $aArgs['selected_date'] : 'seven_days'; ?>">

                    <input type="text" value="<?php echo '' . ($aArgs['start_date'] === null || $aArgs['start_date'] === '전체') ? date('Y-m-d', strtotime(libConfig::SEVEN_DAYS)) : $aArgs['start_date']; ?>" id="start_date" name="start_date" class="fText gDate <?php echo ($aArgs['start_date'] === '전체' ? 'readonly' : ''); ?>" <?php echo ($aArgs['start_date'] === '전체' ? 'readonly' : ''); ?> >

                    <a href="javascript:;" id="start_date" class="btnIcon icoCal img_cal1">
                        <span>달력보기</span>
                    </a>
                    ~
                    <input type="text" value="<?php echo '' . ($aArgs['end_date'] === null) ? date('Y-m-d') : $aArgs['end_date']; ?>" id="end_date" name="end_date" class="fText gDate <?php echo ($aArgs['start_date'] === '전체' ? 'readonly' : ''); ?>"  <?php echo ($aArgs['start_date'] === '전체' ? 'readonly' : ''); ?>>

                    <a href="javascript:;" id="end_date" class="btnIcon icoCal img_cal2">
                            <span>달력보기</span>
                    </a>
                </td>
            </tr>
             <tr>
                <th scope="row">상태</th>
                <td colspan="2">
                    <?php
                    if (libValid::isArray($aArgs['status']) == true) {
                    $aChecked = array_values(array_filter($aArgs['status'], function($mVal) { return $mVal !== '';})); //array (2)

                    ?>
                    <label class="gLabel eSelected"><input  type="checkbox" name="status[]" class="fChk allChk condiAllChk" checked/> 전체</label>
                        <?php foreach($aCondition as $iKey => $sLabel) {
                            if(in_array($sLabel, $aChecked) === true) { ?>
                                <label class="gLabel eSelected"><input type="checkbox" name="status[]" class="fChk rowChk condiChk" value="<?php echo $sLabel; ?>" checked><?php echo $sLabel; ?></label>
                        <?php } else { ?>
                                <label class="gLabel eSelected"><input type="checkbox" name="status[]" class="fChk rowChk condiChk" value="<?php echo $sLabel; ?>"><?php echo $sLabel; ?></label>
                        <?php }
                        }
                    } else {
                    ?>
                    <label class="gLabel eSelected"><input  type="checkbox" name="status[]" class="fChk allChk condiAllChk" checked/> 전체</label>
                    <?php
                        foreach ($aCondition as $iKey => $aValue) {
                    ?>
                        <label class="gLabel eSelected"><input  type="checkbox" value="<?php echo $aValue; ?>" name="status[]" class="fChk rowChk condiChk" checked><?php echo _($aValue); ?></label>
                    <?php
                        }
                    }
                    ?>
                </td>
            </tr>
             <tr>
                <th scope="row">기관코드</th>
                <td colspan="2">
                    <?php
                    if (libValid::isArray($aArgs['department_id']) == true) {
                    $aChecked = array_values($aArgs['department_id']);
                    ?>
                    <label class="gLabel eSelected"><input  type="checkbox" name="department_id[]" class="fChk allChk depAllChk" checked/> 전체</label>
                        <?php foreach($aDepartments as $iKey => $aDepartment) {
                            if(in_array($iKey, $aChecked) === true) { ?>
                                <label class="gLabel eSelected"><input type="checkbox" name="department_id[]" class="fChk rowChk depRowChk" value="<?php echo $iKey; ?>" checked><?php echo mb_convert_encoding($aDepartment[0]['department_name'], "HTML-ENTITIES", "UTF-8") . '(' . $aDepartment[0]['department_code'] . ')'; ?></label>
                        <?php } else { ?>
                                <label class="gLabel eSelected"><input type="checkbox" name="department_id[]" class="fChk rowChk depRowChk" value="<?php echo $iKey; ?>"><?php echo mb_convert_encoding($aDepartment[0]['department_name'], "HTML-ENTITIES", "UTF-8") . '(' . $aDepartment[0]['department_code'] . ')'; ?></label>
                        <?php }
                        }
                    } else {
                    ?>
                    <label class="gLabel eSelected"><input  type="checkbox" name="department_id[]" class="fChk allChk depAllChk" checked/> 전체</label>
                    <?php
                        foreach ($aDepartments as $iKey => $aDepartment) {
                    ?>
                        <label class="gLabel eSelected"><input  type="checkbox" value="<?php echo $iKey; ?>" name="department_id[]" class="fChk rowChk depRowChk" checked><?php echo mb_convert_encoding($aDepartment[0]['department_name'], "HTML-ENTITIES", "UTF-8") . '(' . $aDepartment[0]['department_code'] . ')'; ?></label>
                    <?php
                        }
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="section">
    <div class="mButton gCenter">
        <a id="btnReceptionSearch" href="#none" class="btnSearch"><span>검색</span></a>
    </div>
</div>
</form>
<div class="section">
    <div class="mState">
        <div class="mTitle">
            <h2>접수처 목록</h2>
        </div>
        <div class="gLeft">
            <p class="total">[총
                <strong class="txtWarn"><?php echo $aPage['total_count']; ?></strong>
                개]
            </p>
        </div>
        <div class="gRight">
            <select id="limit" name="limit" class="fSelect">
              <option value="10" <?php echo '' . ($aPage['page_record'] === 10) ? 'selected' : ''; ?> >10개씩 보기</option>
              <option value="20" <?php echo '' . ($aPage['page_record'] === 20) ? 'selected' : ''; ?> >20개씩 보기</option>
              <option value="30" <?php echo '' . ($aPage['page_record'] === 30) ? 'selected' : ''; ?> >30개씩 보기</option>
              <option value="50" <?php echo '' . ($aPage['page_record'] === 50) ? 'selected' : ''; ?> >50개씩 보기</option>
              <option value="100" <?php echo '' . ($aPage['page_record'] === 100) ? 'selected' : ''; ?> >100개씩 보기</option>
            </select>
        </div>
    </div>
    <div class="mCtrl typeHeader">
        <form id="formDelete" action="[link=admin/reception/delete]" method="POST">
        <div class="gLeft">
            <span class="txtLess">선택한 항목을</span>
            <a href="#none" class="btnNormal btnDeleteReception" id="">
                <span>
                    <em class="icoDel"></em>
                    삭제
                </span>
            </a>
        </div>
        <div class="gRight">
            <a href="[link=admin/reception/details]" class="btnCtrl">
                <span>접수처 등록</span>
            </a>
        </div>
    </div>
    <div class="mBoard">
        <table border="1" summary="" class="eChkColor eChkTr" id="tblNotiList">
            <caption>Notification 리스트</caption>
            <colgroup>
                <col class="chk">
                <col style="width:50px;">
                <col style="width:90px;">
                <col style="width:90px;">
                <col style="width:auto;">
                <col style="width:auto;">
                <col style="width:auto;">
                <col class="date">
            </colgroup>
            <thead>
                <tr>
                    <th scope="col">
                        <input type="checkbox" class="allChk tableChk">
                    </th>
                    <th>No</th>
                    <th>상태</th>
                    <th>기관코드</th>
                    <th>자격증</th>
                    <th>접수처</th>
                    <th>시험장소</th>
                    <th>등록일</th>
                </tr>
            </thead>
            <tbody class="center">
                <?php 
                    $iNum = $aArgs['total_count'] - (($aArgs['page'] - 1) * $aArgs["limit"]);
                    if (libValid::isArray($aReceptions) === true) {
                        if ($iNum > 0) {
                            foreach ($aReceptions as $aReception) {
                ?>
                    <tr>
                        <td><input type="checkbox" name="reception[]" class="rowChk" value="<?php echo $aReception['seq_no']; ?>"></td>
                        <td><?php echo $iNum; ?></td>
                        <td><?php echo $aReception['condition_status'];?></td>
                        <td><?php echo $aDepartments[$aReception['ixnn_reception_department_seq_no']][0]['department_name'] . '(' . $aDepartments[$aReception['ixnn_reception_department_seq_no']][0]['department_code'] . ')'; ?></td>
                        <td><?php echo $aCertificates[$aReception['ixnn_certificate_seq_no']][0]['name']; ?></td>
                        <td><a href="[link=admin/reception/details?seq_no=<?php echo $aReception['seq_no']; ?>]" class="txtLink"><?php echo htmlspecialchars(stripcslashes($aReception['name']), ENT_QUOTES, "UTF-8"); ?></a></td>
                        <td><?php echo $aReception['test_site']; ?></td>
                        <td><?php echo date_format(date_create($aReception['upd_timestamp']), 'Y-m-d'); ?></td>
                    </tr>
                <?php
                                $iNum--;
                            }
                        }
                    } else {
                ?>

                <tr>
                    <td colspan="8">등록된 접수처가 없습니다.</td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
    <div class="mCtrl typeFooter">
        <div class="gLeft">
            <span class="txtLess">선택한 항목을</span>
            <a href="#none" class="btnNormal btnDeleteReception" id="">
                <span>
                    <em class="icoDel"></em>
                    삭제
                </span>
            </a>
        </div>
        <div class="gRight">
            <a href="[link=admin/reception/details]" class="btnCtrl">
                <span>접수처 등록</span>
            </a>
        </div>
    </div>
    </form>
</div>
<?php echo $aPaging['sPaging']; ?>
<div class="mHelp typeInfo">
    <h2>도움말</h2>
    <div class="content">
        <p>공급사(교육파트너)는 직접 등록한 접수처 목록만 확인 가능합니다.</p>
    </div>
</div>
