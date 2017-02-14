<div class="headingArea">
    <div class="mTitle">
        <h1>신청 관리</h1>
    </div>
</div>

<form id="search_application_form" action="[link=admin/application/index]" method="GET">
<input type="hidden" name="sort" value="<?php echo '' . ($aArgs['sort'] === null) ? 'D' : $aArgs['sort'] ?>">
<div class="section index">
    <div class="mBoard gSmall">
        <table border="1" summary="" class="eChkColor">
            <tr>
                <th scope="row">자격증</th>
                <td colspan="2">
                    <select class="fSelect" id="ixnn_certificate_seq_no" name="ixnn_certificate_seq_no">
                            <?php
                                if (libValid::isArray($aCertificates) === true) {
                                    foreach($aCertificates as $iKey => $aCertificate) {
                            ?>
                                <option value="<?php echo $iKey; ?>" <?php echo '' . ((int)$aArgs['ixnn_certificate_seq_no'] === (int)$iKey) ? 'selected' : ''; ?> ><?php echo $aCertificate[0]['name']; ?></option>
                            <?php
                                    }
                                }
                            ?>
                        </select>
                </td>
            </tr>

            <tr>
                <th scope="row">접수처</th>
                <td colspan="2">
                    <select class="fSelect" name="supplier_id" id="supplier_id">
                    <?php 
                        if ($aArgs['mall_version'] === libConfig::SUPER_ADMIN || $aArgs['mall_version'] === libConfig::ADMIN) {
                    ?>
                        <option value="">전체</option>
                    <?php
                        }
                    ?>
                    <?php
                        if (libValid::isArray($aSuppliers) === true && $aArgs['mall_version'] === libConfig::SUPER_ADMIN || $aArgs['mall_version'] === libConfig::ADMIN) {
                            foreach($aSuppliers as $iSupplier) {
                    ?>
                        <option value="<?php echo $iSupplier['supplier_id']; ?>"  <?php echo '' . ($aArgs['supplier_id'] === $iSupplier['supplier_id']) ? 'selected' : ''; ?> ><?php echo $iSupplier['supplier_id']; ?></option>
                    <?php
                            }
                        } else if ($aArgs['mall_version'] === libConfig::SUPPLIER) {
                    ?>
                            <option value="<?php echo $aArgs['user_id'];?>">전체</option>
                            <option value="<?php echo $aArgs['user_id'];?>"><?php echo htmlspecialchars(stripcslashes($aArgs['user_id']), ENT_QUOTES, 'UTF-8');?></option>
                    <?php
                        }
                    ?>
                    </select>
                    <select class="fSelect" name="reception_id" id="reception_id">

                    <?php
                        if (libValid::isArray($aReceptions) === true) {
                    ?>
                        <option value="0">전체</option>
                    <?php
                            foreach($aReceptions as $aReception) {
                    ?>
                        <option value="<?php echo $aReception['seq_no']; ?>" <?php echo '' . ((int)$aArgs['reception_id'] === (int)$aReception['seq_no']) ? 'selected' : ''; ?> ><?php echo htmlspecialchars(stripcslashes($aReception['name']), ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php
                            }
                        } else {
                    ?>
                        <option selected disabled>수신을 먼저 등록하십시오.</option>
                    <?php
                        }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">신청일</th>
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
                    $aChecked = array_values($aArgs['status']);
                    ?>
                    <label class="gLabel eSelected"><input  type="checkbox" name="status[]" class="fChk allChk condiAllChk" checked/> 전체</label>
                        <?php foreach($aConditions as $iKey => $sLabel) {
                            if(in_array($iKey, $aChecked) === true) { ?>
                                <label class="gLabel eSelected"><input type="checkbox" name="status[]" class="fChk rowChk condiChk" value="<?php echo $sLabel; ?>" checked><?php echo $sLabel; ?></label>
                        <?php } else { ?>
                                <label class="gLabel eSelected"><input type="checkbox" name="status[]" class="fChk rowChk condiChk" value="<?php echo $sLabel; ?>"><?php echo $sLabel; ?></label>
                        <?php }
                        }
                    } else {
                    ?>
                    <label class="gLabel eSelected"><input  type="checkbox" name="status[]" class="fChk allChk condiAllChk" checked/> 전체</label>
                    <?php
                        foreach ($aConditions as $iKey => $aValue) {
                    ?>
                        <label class="gLabel eSelected"><input  type="checkbox" value="<?php echo $aValue; ?>" name="status[]" class="fChk rowChk condiChk" checked><?php echo _($aValue); ?></label>
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
        <a id="btnApplicationSearch" href="#none" class="btnSearch"><span>검색</span></a>
    </div>
</div>
</form>
<div class="section">
    <div class="mState">
        <div class="mTitle">
            <h2>신청 목록</h2>
        </div>
        <div class="gLeft">
            <p class="total">[총
                <strong class="txtWarn"><?php echo $aPage['total_count']; ?></strong>개]
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
        <form id="formDelete" action="[link=admin/application/delete]" method="POST">
        <div class="gLeft">
            <span class="txtLess">선택한 항목을</span>
            <a href="#none" class="btnNormal btnDeleteApplication" id="">
                <span>
                    <em class="icoDel"></em>
                    삭제
                </span>
            </a>
        </div>
        <div class="gRight">
            <?php 
            if ($aArgs['mall_version'] === libConfig::SUPER_ADMIN || $aArgs['mall_version'] === libConfig::ADMIN) { ?>
                <a href="#none" class="btnNormal btnDownloadExcell"><span><em class="icoXls"></em> 엑셀다운로드<em class="icoLink"></em></span> </a>
            <?php 
                }
            ?>
            <a href="[link=admin/application/details]" class="btnCtrl">
                <span>등록하기</span>
            </a>
        </div>
    </div>
    <div class="mBoard">
        <table border="1" summary="" class="eChkColor eChkTr" id="tblNotiList">
            <caption>Notification 리스트</caption>
            <colgroup>
                <col class="chk">
                <col style="width:50px;">
                <col style="width:auto;">
                <col style="width:110px;">
                <col style="width:80px;">
                <col style="width:auto;">
                <col style="width:75px;">
                <col class="date">
                <col style="width:70px;">
                <col style="width:85px;">
                <col style="width:100px;">
            </colgroup>
            <thead>
                <tr>
                    <th scope="col">
                        <input type="checkbox" class="allChk tableChk">
                    </th>
                    <th scope="col">No</th>
                    <th scope="col">자격증</th>
                    <th scope="col">아이디</th>
                    <th scope="col">이름</th>
                    <th scope="col">접수처</th>
                    <th scope="col">상태</th>
                    <th scope="col">신청일</th>
                    <th scope="col">점수</th>
                    <th scope="col">
                        <input id="sort" type="hidden" name="sort" value="<?php echo '' . ($aArgs['sort'] === null) ? 'D' : $aArgs['sort'] ?>">
                        <strong id="btnSortApplication" class="array <?php echo '' . ($aArgs['sort'] === 'D') ? 'descend' : 'ascend'?>">합격여부<button  type="button">내림차순 정렬</button></strong>
                    </th>
                    <th scope="col">자격증 발급</th>
                </tr>
            </thead>
            <tbody class="center">
            <form id="formDelete" action="[link=admin/application/delete]" method="POST">
               <?php 
                    if ($aArgs['sort'] === 'A') {
                        $iNum = $aArgs['total_count'] - (($aArgs['total_count'] - ($aArgs['page'] - 1) * $aArgs['limit']) - 1);
                    } else {
                        $iNum = $aArgs['total_count'] - (($aArgs['page'] - 1) * $aArgs["limit"]);
                    }
                    if (libValid::isArray($aApplications) === true) {
                        foreach ($aApplications as $aApplication) {
                ?>
                    <tr>
                        <td><input type="checkbox" class="fChk rowChk" name="application_id[]" value="<?php echo $aApplication['seq_no']; ?>"></td>
                        <td><?php echo $iNum; ?></td>
                        <td><?php echo htmlspecialchars($aApplication['certificate'], ENT_QUOTES, "UTF-8"); ?></td>
                        <td><?php echo '' . ($aApplication['applicant_id'] === null) ? '-' : $aApplication['applicant_id'] ; ?></td>
                        <td><?php echo stripcslashes($aApplication['applicant_name_kr']); ?></td>
                        <td><a href="[link=admin/application/details?seq_no=<?php echo $aApplication['seq_no']; ?>]" class="txtLink"><?php echo htmlspecialchars(stripcslashes($aApplication['reception']), ENT_QUOTES, "UTF-8"); ?></a></td>
                        <td><?php echo $aApplication['condition_status']; ?></td>
                        <td><?php echo date('Y-m-d', strtotime($aApplication['ins_timestamp'])); ?></td>
                        <td><?php echo '' . ($aApplication['written_exam_score'] === null) ? '-' : $aApplication['written_exam_score']; echo ' / '; echo '' . ($aApplication['practical_test_score'] === null) ? '-' : $aApplication['practical_test_score']; ?></td>
                        <td><?php echo $aApplication['test_result'] ;?></td>
                        <td>
                        <?php 
                            if ($aApplication['condition_status'] === $aConditions['60']  || $aApplication['condition_status'] === $aConditions['50']) {
                        ?>
                            <a href="#none" class="btnNormal btnPrint" onclick="window.open('[link=admin/application/certificate]?seq_no=<?php echo $aApplication[seq_no]; ?>', 'mywin', 'left=20,top=20,width=620,height=920,toolbar=1,resizable=0');"><span>출력하기<em class="icoLink"></em></span></a><p>[최초 발급일]</p><p><?php echo '' . ($aApplication['issued_date'] === null) ? '-' : $aApplication['issued_date']; ?></p>
                        <?php

                            } else {
                                echo '-';
                            }
                        ?></td>
                    </tr>
                <?php
                            if($aArgs['sort'] === 'A') {
                                $iNum++;
                            } else {
                                $iNum--;
                            }
                        }
                    } else {
                ?>

                <tr>
                    <td colspan="11">신청 내역이 없습니다.</td>
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
            <a href="#none" class="btnNormal btnDeleteApplication" id="">
                <span>
                    <em class="icoDel"></em>
                    삭제
                </span>
            </a>
        </div>
        <div class="gRight">
            <?php 
             if ($aArgs['mall_version'] === libConfig::SUPER_ADMIN || $aArgs['mall_version'] === libConfig::ADMIN) { ?>
                <a href="#none" class="btnNormal btnDownloadExcell"><span><em class="icoXls"></em> 엑셀다운로드<em class="icoLink"></em></span> </a>
            <?php 
                }
            ?>
            <a href="[link=admin/application/details]" class="btnCtrl">
                <span>등록하기</span>
            </a>
        </div>
    </div>
    </form>
</div>
<?php echo $aPaging['sPaging']; ?>
<div class="mHelp typeInfo">
    <h2>도움말</h2>
    <div class="content">
        <ol>
            <li>1. 공급사(교육파트너)는 직접 등록한 접수처에 대한 신청목록만 확인 가능합니다.</li>
            <li>
                2. 각 상태는 다음과 같은 진행 단계를 나타냅니다.
                <ul>
                    <li><strong>접수대기</strong> : 온라인으로 신청만 접수된 상태 / <strong>접수완료</strong> : 접수된 내용을 공급사가 확인한 상태 / <strong>채점완료</strong> : 공급사가 점수를 입력한 상태</li>
                    <li><strong>승인요청</strong> : 공급사가 대표운영자에게 발급승인을 요청한 상태 / <strong>발급승인</strong> : 대표운영자가 발급을 승인하여 자격증 발급이 가능한 상태</li>
                    <li><strong>발급완료</strong> : 자격증 출력을 1회 이상 완료한 상태 / <strong>보완필요</strong> : 공급사가 자격증 발급승인 요청을 했으나, 대표운영자가 반려한 상태</li>
                </ul>
            </li>
            <li>3. 공급사(교육파트너) 채점완료 후 자격증 최종발급 전 대표운영자의 승인단계가 필요합니다.</li>
        </ol>
    </div>
</div>
