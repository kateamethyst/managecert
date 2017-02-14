<div class="headingArea">
    <div class="mTitle">
        <h1>신청 관리</h1>
    </div>
</div>
<div class="section">
    <div class="mBoard">
        <form id="application_update_form" action="[link=admin/application/save]" method="POST">
            <input id="seq_no" type="hidden" value="<?php echo $aApplication['seq_no']; ?>" name="seq_no">
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
                        <p><?php echo htmlspecialchars($aApplication['certificate'], ENT_QUOTES, "UTF-8"); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">접수처</th>
                    <td colspan="3">
                        <input type="hidden" class="fText" name="reception" id="reception" value="<?php echo $aApplication['reception']; ?>">
                        <p><?php echo htmlspecialchars($aApplication['reception_name'], ENT_QUOTES, "UTF-8"); ?></p>

                    </td>
                </tr>
                <tr>
                    <th scope="row">소속</th>
                    <td>
                        <p><?php echo htmlspecialchars($aApplication['department_name'], ENT_QUOTES, "UTF-8"); ?></p>
                    </td>
                    <th scope="row">기관코드</th>
                    <td>
                        <input type="hidden" id="department_code" name="department_code" value="<?php echo $aApplication['department_code']; ?>">
                        <p><?php echo $aApplication['department_code']; ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">교육형태</th>
                    <td><?php echo htmlspecialchars($aApplication['education_type'], ENT_QUOTES, "UTF-8"); ?></td>
                    <th>시험회차</th>
                    <td>
                        <p><?php echo htmlspecialchars($aApplication['nth_test'], ENT_QUOTES, "UTF-8"); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">상태</th>
                    <td colspan="3">
                        <span id="status"><?php echo $aApplication['condition_status']; ?></span>
                        <input id="condition_status" value="<?php echo $aApplication['condition_status']; ?>" type="hidden" class="fText" name="status">
                            <?php 
                                if ($bEditable === true) {
                            ?>
                                <a href="#layerTest1" class="btnNormal eLayerClick"><span>수정</span></a>
                            <?php
                                }
                            ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">이름(한글) <span class="icoRequired"></span></th>
                    <td colspan="3">
                        <?php 
                        if ($bEditable === true) {
                        ?>
                            <input type="text" name="korean_name" class="fText" style="width:160px;" value="<?php echo htmlspecialchars($aApplication['applicant_name_kr'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php
                            } else {
                                echo htmlspecialchars($aApplication['applicant_name_kr'], ENT_QUOTES, "UTF-8");
                        ?>
                        <?php
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">이름(영문) <span class="icoRequired"></span></th>
                    <td colspan="3">
                        <?php 
                        if ($bEditable === true) {
                        ?>
                            <input type="text" name="english_name" class="fText" style="width:160px;" value="<?php echo htmlspecialchars($aApplication['applicant_name_en'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php
                            } else {
                                echo htmlspecialchars($aApplication['applicant_name_en'], ENT_QUOTES, "UTF-8");
                        ?>
                        <?php
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">생년월일 <span class="icoRequired"></span></th>
                    <td colspan="3">
                        <?php 
                        if ($bEditable === true) {
                        ?>
                            <input id="birthday" type="text" name="birthday" class="fText" style="width:160px;" value="<?php echo $aApplication['birthday']; ?>">
                        <?php
                            } else {
                                echo htmlspecialchars($aApplication['birthday'], ENT_QUOTES, "UTF-8");

                        ?>
                        <?php
                            }
                        ?>
                        <span class="txtInfo">생년월일 8자리를 숫자로만 입력하세요. (예: 20001212)</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">휴대전화 <span class="icoRequired"></span></th>
                    <td colspan="3">
                    <?php 
                    if ($bEditable === true) {
                        $aNumber = explode('-', $aApplication['applicant_cell']);
                    ?>
                        <select id="number_item1" name="number_item1">
                            <option value="010" <?php echo '' . ($aNumber[0] === '010') ? 'selected' : '' ;?> >010</option>
                            <option value="011" <?php echo '' . ($aNumber[0] === '011') ? 'selected' : '' ;?> >011</option>
                            <option value="016" <?php echo '' . ($aNumber[0] === '016') ? 'selected' : '' ;?> >016</option>
                            <option value="017" <?php echo '' . ($aNumber[0] === '017') ? 'selected' : '' ;?> >017</option>
                            <option value="018" <?php echo '' . ($aNumber[0] === '018') ? 'selected' : '' ;?> >018</option>
                            <option value="019" <?php echo '' . ($aNumber[0] === '019') ? 'selected' : '' ;?> >019</option>
                        </select>-
                        <input id="number_item2" type="text" name="number_item2" value="<?php echo $aNumber[1]; ?>" class="fText" style="width:50px;">-
                        <input id="number_item3" type="text" name="number_item3" value="<?php echo $aNumber[2]; ?>" class="fText" style="width:50px;">
                    <?php
                        } else {
                            echo htmlspecialchars($aApplication['applicant_cell'], ENT_QUOTES, "UTF-8");
                    ?>
                    <?php
                        }
                    ?> 
                    </td>
                </tr>
                <tr>
                    <th scope="row">이메일</th>
                    <td colspan="3">
                        <?php 
                        if ($bEditable === true) {
                        ?>
                            <input type="text" name="email" class="fText" style="width:182px;" value="<?php echo htmlspecialchars($aApplication['email'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php
                            } else {
                                echo htmlspecialchars($aApplication['email'], ENT_QUOTES, "UTF-8");
                        ?>
                        <?php
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">신청일</th>
                    <td colspan="3">
                        <p><?php echo date('Y-m-d', strtotime($aApplication['ins_timestamp'])); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">필기점수</th>
                    <td colspan="3">
                        <?php 
                        if ($bEditable === true) {
                        ?>
                        <input type="text" value="<?php echo htmlspecialchars($aApplication['written_exam_score'], ENT_QUOTES, 'UTF-8'); ?>" style="width:50px;" name="written_exam_score" class="fText">
                        <?php
                            } else {
                                echo htmlspecialchars($aApplication['written_exam_score'], ENT_QUOTES, "UTF-8");
                        ?>
                        <?php
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">실기점수</th>
                    <td colspan="3">
                        <?php 
                        if ($bEditable === true) {
                        ?>
                            <input type="text" value="<?php echo htmlspecialchars($aApplication['practical_test_score'], ENT_QUOTES, 'UTF-8'); ?>" style="width:50px;" name="practical_test_score" class="fText">
                        <?php
                            } else {
                                echo htmlspecialchars($aApplication['practical_test_score'], ENT_QUOTES, "UTF-8");
                        ?>
                        <?php
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">합격여부</th>
                    <td colspan="3">
                        <?php 
                        if ($bEditable === true) {
                        ?>
                            <select name="remarks" class="fSelect">
                            <option value="미입력" <?php echo '' . ($aApplication['test_result'] === '미입력') ? 'selected' : ''; ?> >미입력</option>
                            <option value="합격"   <?php echo '' . ($aApplication['test_result'] === '합격')  ? 'selected' : ''; ?> >합격</option>
                            <option value="불합격" <?php echo '' . ($aApplication['test_result'] === '불합격') ? 'selected' : ''; ?> >불합격</option>
                        </select>
                        <?php
                            } else {
                                echo htmlspecialchars($aApplication['test_result'], ENT_QUOTES, "UTF-8");
                        ?>
                        <?php
                            }
                        ?>
                       
                    </td>
                </tr>
                <tr>
                    <th scope="row">자격증 발급번호</th>
                    <td colspan="3">
                        <p>
                            <?php
                                if ($aApplication['certification_no'] !== null) {
                            ?>
                                <p>
                                    <?php echo $aApplication['certification_no']; ?>
                                    <a href="#none" class="btnNormal btnPrint" onclick="window.open('[link=admin/application/certificate]?seq_no=<?php echo $aApplication[seq_no]; ?>', 'mywin', 'left=20,top=20,width=620,height=920,toolbar=1,resizable=0');"><span>출력하기<em class="icoLink"></em></span>
                                    </a>
                                </p>

                            <?php
                                } else {
                                    echo '-';
                                }
                            ?>
                        </p>
                    </td>
                </tr>
                    <th scope="row">최초 발급일</th>
                    <td colspan="3">
                        <p><?php echo ''. ($aApplication['issued_date'] === null) ? '-' : $aApplication['issued_date']; ?></p>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<div class="mButton gCenter">
    <?php 
    if ($bEditable === true) {
    ?>
        <a id="btnUpdateApplication" href="#" class="btnSubmit"><span>등록</span></a>
    <?php
        }
    ?>
    <a href="[link=admin/application/index]" class="btnEm"><span>취소</span></a>
</div>

<div id="layerTest1" class="mLayer gSmall">
    <h2>상태 수정</h2>
    <div class="wrap">
        <div class="mTitle">
            <h3>변경할 상태를 선택하세요.</h3>
        </div>
        <div class="mTitle">
            <div class="mBoard">
                <table>
                    <tr>
                        <th>상태</th>
                        <td>
                            <select id="conditions" class="fSelect">
                                <?php
                                    if ($aArgs['mall_version'] === libConfig::SUPER_ADMIN || $aArgs['mall_version'] === libConfig::ADMIN) {
                                ?>
                                <option value="발급승인">발급승인</option>
                                <option value="보완필요">보완필요</option>
                                <option disabled>--------------</option>
                                <?php
                                    }
                                ?>
                                <option value="접수대기" >접수대기</option>
                                <option value="접수완료" >접수완료</option>
                                <option value="채점완료" >채점완료</option>
                                <option value="승인요청" >승인요청</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="footer">
        <a id="btnSaveCondition" href="#none" class="btnCtrl"><span>저장</span></a>
        <a href="#none" class="btnNormal eClose"><span>취소</span></a>
    </div>
    <button type="button" class="btnClose eClose">닫기</button>
</div>
