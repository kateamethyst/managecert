<div class="headingArea">
    <div class="mTitle">
        <h1>접수처 관리</h1>
    </div>
</div>
<div class="mBoard">
    <form id="reception_form" action="[link=admin/reception/save]" method="POST">
        <input type="hidden" class="fText" name="seq_no" value="<?php echo $aReception['seq_no']; ?>">
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
                        <?php
                            echo _($aCertificates[$aReception['ixnn_certificate_seq_no']][0]['name']);
                        ?>
                </td>
            </tr>
            <tr>
                <th scope="row">접수처</th>
                <td colspan="3">
                    <?php echo htmlspecialchars(stripcslashes($aReception['name']), ENT_QUOTES, "UTF-8"); ?>

                </td>
            </tr>
            <tr>
                <th scope="row">상태</th>
                <td colspan="3">
                <select class="fSelect" name="status">
                    <option value="대기" <?php echo ($aReception['condition_status'] === '대기') ? 'selected' : ''; ?> >대기</option>
                    <option value="진행중" <?php echo ($aReception['condition_status'] === '진행중') ? 'selected' : ''; ?>>진행중</option>
                    <option value="종료" <?php echo ($aReception['condition_status'] === '종료') ? 'selected' : ''; ?>>종료</option>
                </select>
                <span class="txtInfo">상태가 '진행중'인 자격증 접수처만 응시생이 선택할 수 있습니다.</span>
                </td>
            </tr>
            <tr>
                <th scope="row">소속</th>
                <td>
                    <p><?php echo _($aDepartments[$aReception['ixnn_reception_department_seq_no']][0]['department_name']);?></p> 
                </td>
                <th scope="row">기관코드</th>
                <td id="department_code"><?php echo _($aDepartments[$aReception['ixnn_reception_department_seq_no']][0]['department_code']);?></td>
            </tr>
            <tr>
                <th scope="row">교육형태</th>
                <td><input type="text" name="exam_category" style="width:100px;" class="fText" value="<?php echo htmlspecialchars($aReception['education_type'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                <th>시험회차</th>
                <td ><input type="text" name="exam_duration" class="fText" style="width:100px;" value="<?php echo htmlspecialchars($aReception['nth_test'],ENT_QUOTES, 'UTF-8'); ?>"></td>
            </tr>
            <tr>
                <th scope="row">시험장소</th>
                <td colspan="3"><input type="text" name="exam_location" style="width:300px;" class="fText" value="<?php echo htmlspecialchars($aReception['test_site'], ENT_QUOTES, 'UTF-8'); ?>"></td>
            </tr>
            <tr>
               <th scope="row">등록일</th>
                <td colspan="3">
                    <?php
                        echo date_format(new DateTime($aReception['upd_timestamp']), 'Y-m-d');
                    ?>
                </td>
            </tr>
        </table>
    </form>
</div>
<div class="mButton gCenter">
    <a id="btnUpdateReception" href="#" class="btnSubmit"><span>등록</span></a>
    <a href="[link=admin/reception/index]" class="btnEm"><span>취소</span></a>
</div>
