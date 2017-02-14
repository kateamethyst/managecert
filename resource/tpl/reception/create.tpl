<div class="headingArea">
    <div class="mTitle">
        <h1>접수처 관리</h1>
    </div>
</div>
<div class="mBoard">
    <form id="reception_form" action="[link=admin/reception/save]" method="POST">
        <table border="1" summary="" class="eChkColor">
            <colgroup>
                <col style="width:135px;">
                <col style="width:auto;">
                <col style="width:135px;">
                <col style="width:auto;">
            </colgroup>
            <tr>
                <th scope="row">자격증</th>
                <td colspan="3">
                    <select class="fSelect" name="certificate_id">
                        <?php
                            if (libValid::isArray($aCertificates) === true) {
                                foreach($aCertificates as $iKey => $aCert) {
                        ?>
                            <option value="<?php echo _($iKey); ?>"><?php echo htmlspecialchars($aCert[0]['name'], ENT_QUOTES, "UTF-8"); ?></option>
                        <?php
                                }
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">접수처 <span class="icoRequired"></span></th>
                <td colspan="3">
                    <input id="reception_name" type="text" name="name" class="fText" style="width:300px;">
                    <span class="txtInfo">접수처 명칭을 기준으로 응시생이 접수처를 선택하기 때문에 구체적인 안내가 필요하며, 중복된 접수처는 등록할 수 없습니다(예: ○○대학교 △△학과 5기)</span>
                </td>
            </tr>
            <tr>
                <th scope="row">상태</th>
                <td colspan="3">
                <select class="fSelect" name="status">
                    <option value="대기">대기</option>
                    <option value="진행중">진행중</option>
                </select>
                <span class="txtInfo">상태가 '진행중'인 자격증 접수처만 응시생이 선택할 수 있습니다.</span>
                </td>
            </tr>
            <tr>
                <th scope="row">선택 <span class="icoRequired"></span></th>
                <td>
                    <select id="dep_seq_no" class="fSelect" name="department_id" required>
                        <option selected disabled value="0"> -선택-</option>
                        <?php
                            if (libValid::isArray($aDepartments) === true) {
                                foreach($aDepartments as $iKey => $aDep) {
                        ?>
                            <option value="<?php echo $iKey; ?>"><?php echo _($aDep[0]['department_name']); ?></option>
                        <?php
                                }
                            } else {
                        ?>
                            <option value="1">개인</option>
                            <option value="2">강사</option>
                            <option value="3">고등학교</option>
                            <option value="4">대학교</option>
                        <?php
                            }
                        ?>
                    </select>
                </td>
                <th scope="row">기관코드</th>
                <td id="department_code">-</td>
            </tr>
            <tr>
                <th scope="row">교육형태</th>
                <td><input type="text" name="exam_category" class="fText" style="width:100px;"></td>
                <th>시험회차</th>
                <td ><input type="text" name="exam_duration" class="fText" style="width:100px;"></td>
            </tr>
            <tr>
                <th scope="row">시험장소</th>
                <td colspan="3"><input type="text" name="exam_location" class="fText" style="width:300px;"></td>
            </tr>
        </table>
    </form>
</div>
<div class="mButton gCenter">
    <a id="btnSaveReception" href="#" class="btnSubmit"><span>등록</span></a>
    <a href="[link=admin/reception/index]" class="btnEm"><span>취소</span></a>
</div>
