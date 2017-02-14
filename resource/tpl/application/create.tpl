<div class="headingArea">
    <div class="mTitle">
        <h1>신청 관리</h1>
    </div>
</div>
<div class="section">
    <div class="mBoard">
        <form id="application_form" action="[link=admin/application/save]" method="POST">
            <table border="1" summary="" class="eChkColor">
                <colgroup>
                    <col style="width:135px;">
                    <col style="width:auto;">
                    <col style="width:135px;">
                    <col style="width:auto;">
                </colgroup>
                <tr>
                    <input id="mall_version" type="hidden" name="mall_version" value="<?php echo $aArgs['mall_version'];?>">
                    <th scope="row" width="10%">자격증</th>
                    <td colspan="3">
                        <select id="certificate_id" class="fSelect" name="certificate_id">
                            <?php
                                if (libValid::isArray($aCertificates) === true) {
                                    foreach($aCertificates as $iKey => $aCertificate) {
                            ?>
                                <option value="<?php echo $iKey; ?>"><?php echo $aCertificate[0]['name']; ?></option>
                            <?php
                                    }
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row" width="10%">접수처 <span class="icoRequired"></span></th>
                    <td colspan="3">
                        <select id="reception" class="fSelect" name="reception_id" required>
                            <option selected disabled>-선택-</option>
                            <?php
                                if (libValid::isArray($aReceptions) === true) {
                                    foreach($aReceptions as $aReception) {
                            ?>
                                <option value="<?php echo $aReception['seq_no']; ?>"><?php echo htmlspecialchars($aReception['name'], ENT_QUOTES, "UTF-8"); ?></option>
                            <?php
                                    }
                                }
                            ?>
                        </select>
                        <span class="txtLight txtInfo">공급사(교육파트너)는 직접 등록한 접수처만 선택 가능합니다.</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">소속</th>
                    <td>
                        <p id="belong">자동생성 </p>
                    </td>
                    <th scope="row">기관코드</th>
                    <td>
                        <p id="education_type">자동생성 </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">교육형태</th>
                    <td>
                        <p id="code">자동생성 </p>
                    </td>
                    <th>시험회차</th>
                    <td >
                        <p id="nth_test">자동생성 </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row" width="10%">상태</th>
                    <td colspan="3">
                        <select name="status" class="fSelect">
                            <option value="접수대기">접수대기</option>
                            <option value="접수대기">접수완료</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">이름(한글) <span class="icoRequired"></span></th>
                    <td colspan="3">
                        <input type="text" name="korean_name" class="fText" required style="width: 160px;">
                    </td>
                </tr>
                <tr>
                    <th scope="row">이름(영문) <span class="icoRequired"></span></th>
                    <td colspan="3">
                        <input type="text" name="english_name" class="fText" required style="width: 160px;">
                    </td>
                </tr>
                <tr>
                    <th scope="row"">생년월일 <span class="icoRequired"></span></th>
                    <td colspan="3">
                        <input id="birthday" type="text" name="birthday" class="fText" required style="width: 160px;">
                        <span class="txtInfo">생년월일 8자리를 숫자로만 입력하세요. (예: 20001212)</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">휴대전화 <span class="icoRequired"></span></th>
                    <td colspan="3">
                        <select id="number_item1" class="fSelect" name="number_item1" required>
                            <option value="010">010</option>
                            <option value="011">011</option>
                            <option value="016">016</option>
                            <option value="017">017</option>
                            <option value="018">018</option>
                            <option value="019">019</option>
                        </select>-
                        <input id="number_item2" type="text" name="number_item2" class="fText" required style="width:50px;">-
                        <input id="number_item3" type="text" name="number_item3" class="fText" required style="width:50px;">
                    </td>
                </tr>
                <tr>
                    <th scope="row">이메일</th>
                    <td colspan="3">
                        <input type="email" name="email" class="fText" required style="width: 182px;">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<div class="mButton gCenter">
    <a id="btnSaveApplication" href="#" class="btnSubmit"><span>등록</span></a>
    <a href="[link=admin/application/index]" class="btnEm"><span>취소</span></a>
</div>
