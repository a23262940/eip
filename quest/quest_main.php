<?php
session_start();
if (!isset($_SESSION['id'])) {
  header("Location: ../main/index.php");
}
error_reporting(0);
include('connect.php');
$sql = "SELECT * FROM art";
$stmt = $db->query($sql);
$iid = $_SESSION['id'];
$sql = "SELECT * FROM users WHERE id='$iid'";
$st = mysqli_query($db, $sql);
$res = mysqli_fetch_array($st);
$_SESSION['username'] = $res['username'];
$_SESSION['email'] = $res['email'];
$_SESSION['name1'] = $res['name1'];
$_SESSION['statu'] = $res['statu'];
$_SESSION['birthday'] = $res['birthday'];
$_SESSION['sex'] = $res['sex'];
$_SESSION['phone'] = $res['phone'];
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <!--html head-->
  <?php include '../php/html_head.php' ?>
  <!-- DataTable 連結 -->
  <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.css"> -->
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.js"></script>
  <!--Database連結-->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

</head>

<body>
  <!--TOP Navbar -->
  <?php include '../php/topnavbar.php'; ?>
  <!--整體-->
  <div class="container-fluid">
    <div class="row">
      <!--left side bar 側邊導航-->
      <div class="col-lg-2">
        <?php include '../php/leftsidenavbar.php'; ?>
      </div>

      <!--所有委任-整體頁面-->
      <main class="col-lg-10 px-md-5">
        <!--所有委任-搜尋區塊-->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">所有會議</h1>
          <button type="button" class="btn table-warning bg-primary text-white" data-bs-toggle="modal" data-bs-target="#revise-Modal">建立文章</button>
          <!--<div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group me-2">
                <input class="form-control" placeholder="搜尋..." required>
              </div>
              <button type="button" class="btn table-warning bg-primary text-white" data-bs-toggle="modal" data-bs-target="#revise-Modal">建立文章</button>
              <select type="button" class="form-select input-group" required>
                <option value="">頁面顯示數量選擇...</option>
                <option>10</option>
                <option>20</option>
                <option>50</option>
              </select>
            </div>-->
        </div>

        <!--所有委任-列表區塊 + 分頁導航-->
        <div class="table-responsive">
          <table class="table table-hover table-sm" id="qtable">
            <thead>
              <tr>
                <th>發布者</th>
                <th>會議日期</th>
                <th>相關部門</th>
                <th>文章標題</th>
                <th>代號</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <!--資料庫資料的欄位-->
              <?php
              $query = mysqli_query($db, "select * from art ORDER BY id DESC") or die(mysqli_error($db));
              while ($result = mysqli_fetch_array($query)) {
                $id = $result['id'];
                $name = $result['name'];
                $title = $result['title'];
                $connect = $result['connect'];
              ?>
                <tr>
                  <td><?php echo $result['name'] ?></td>
                  <td><?php echo $result['time'] ?></td>
                  <td><?php echo $result['depart'] ?></td>
                  <td><?php echo $result['title'] ?></td>
                  <td><?php echo $result['qid'] ?></td>

                  <td>
                    <a href="details.php?del=<?php echo $result['id'] ?>" class="btn btn-primary">詳細內容</a>
                    <a href="../cloud/cloud_main.php#questcloud/<?php echo $result['qid']; ?>" class="btn btn-primary">附加檔案</a>
                    <a href="board.php?del=<?php echo $result['qid'] ?>" class="btn btn-primary">討論區</a>
                    <?php if (($_SESSION['id'] == $result['uid']) || ($_SESSION['statu'] == "管理者")) { ?><a href="revise2.php?del=<?php echo $result['id'] ?>" class="btn btn-primary">編輯</a>
                      <a href="delete2.php?del=<?php echo $result['id'] ?>" class="btn btn-danger" onclick="return confirm('確定要刪除文章?');">刪除</a><?php } ?>
                  </td>

                </tr>

              <?php } ?>
            </tbody>
            <tfoot>
              <th>發布者</th>
              <th>會議日期</th>
              <th>相關部門</th>
              <th>文章標題</th>
              <th>代號</th>
              <th></th>
            </tfoot>
          </table>
          <!--頁籤-->
          <!--<nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
              <li class="page-item">
                <a class="page-link" href="#" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              </li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>
            </ul>
          </nav>-->
        </div>

      </main>

    </div>
  </div>

  <!-- footer copyright-->
  <?php include '../php/footer.php'; ?>

  <!--防呆警告 模态框（Modal) **沒使用到-->
  <div class="modal fade" id="dum-delete-Modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
            <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
            <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z" />
          </svg>
          <h5 class="modal-title">確定刪除?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <form class="form-horizontal">
            <div class="form-group">
              <label class="control-label p-2">刪除代碼為:446611(6位隨機亂數)</label>
              <input type="text" class="form-control" placeholder="請輸入對應的刪除代碼..." required>
            </div>

            <div class="modal-footer">
              <div class="form-group">
                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-danger">確定</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                </div>
              </div>
            </div>

          </form>
        </div>

      </div>
    </div>
  </div>

  <!--建立委託 模态框（Modal)-->
  <div class="modal fade" id="revise-Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
          </svg>
          <h5 class="modal-title">新增文章</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="modal2.php" method="post" class="form-horizontal">
            <div class="form-group row">
              <label class="col-sm-3 control-label p-2">發布者:</label>
              <div class="col-sm-9">
                <input type="text" name="name" class="form-control" value="<?php echo $_SESSION["name1"] ?>" readonly>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label p-2">會議日期:</label>
              <div class="col-sm-9">
                <input type="date" name="time" class="form-control" placeholder="日期" required>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label p-2">相關部門:</label>
              <div class="col-sm-9">
                <input type="checkbox" name="depart[]" value=" 開發部 " />開發部
                <input type="checkbox" name="depart[]" value=" 會計部 " />會計部
                <input type="checkbox" name="depart[]" value=" 行銷部 " />行銷部
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label p-2">身分:</label>
              <div class="col-sm-9">
                <input type="text" name="statu" class="form-control" value="<?php echo $_SESSION["statu"] ?>" readonly>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label p-2">標題:</label>
              <div class="col-sm-9">
                <input type="text" name="title" class="form-control" placeholder="自行輸入..." required>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label p-2">文章內容:</label>
              <div class="col-sm-9">
                <textarea type="text" name="connect" class="form-control" placeholder="自行輸入..." style="height: 100px" required></textarea>
              </div>
            </div>

            <input type="hidden" name="uid" class="form-control" value="<?php echo $_SESSION["id"] ?>">

            <div class="modal-footer">
              <div class="ok">
                <button type="submit" name="btn1" class="btn btn-danger" onclick="return confirm('確定要新增文章?');"> 完成 </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- 中文化 -->
  <script>
    //jQuery表單
    $(document).ready(function() {
      $('#qtable').DataTable({
        //中文化
        "language": {
          "processing": "處理中...",
          "loadingRecords": "載入中...",
          "lengthMenu": "顯示 _MENU_ 項結果",
          "zeroRecords": "沒有符合的結果",
          "info": "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
          "infoEmpty": "顯示第 0 至 0 項結果，共 0 項",
          "infoFiltered": "(從 _MAX_ 項結果中過濾)",
          "search": "搜尋:",
          "paginate": {
            "first": "第一頁",
            "previous": "上一頁",
            "next": "下一頁",
            "last": "最後一頁"
          },
          "aria": {
            "sortAscending": ": 升冪排列",
            "sortDescending": ": 降冪排列"
          },
          "emptyTable": "目前沒有資料",
          "datetime": {
            "previous": "上一頁",
            "next": "下一頁",
            "hours": "時",
            "minutes": "分",
            "seconds": "秒",
            "amPm": [
              "上午",
              "下午"
            ]
          },
          "searchBuilder": {
            "add": "新增條件",
            "condition": "條件",
            "deleteTitle": "刪除過濾條件",
            "button": {
              "_": "複合查詢 (%d)",
              "0": "複合查詢"
            },
            "clearAll": "清空",
            "conditions": {
              "array": {
                "contains": "含有",
                "empty": "為空",
                "equals": "等於",
                "not": "不為",
                "notEmpty": "不為空"
              },
              "date": {
                "after": "大於",
                "before": "小於",
                "between": "在其中",
                "empty": "為空",
                "equals": "等於",
                "not": "不為",
                "notBetween": "不在其中",
                "notEmpty": "不為空"
              },
              "number": {
                "between": "在其中",
                "empty": "為空",
                "equals": "等於",
                "gt": "大於",
                "gte": "大於等於",
                "lt": "小於",
                "lte": "小於等於",
                "not": "不為",
                "notBetween": "不在其中",
                "notEmpty": "不為空"
              },
              "string": {
                "contains": "含有",
                "empty": "為空",
                "endsWith": "字尾為",
                "equals": "等於",
                "not": "不為",
                "notEmpty": "不為空",
                "startsWith": "字首為"
              }
            },
            "data": "欄位",
            "leftTitle": "群組條件",
            "logicAnd": "且",
            "logicOr": "或",
            "rightTitle": "取消群組",
            "title": {
              "_": "複合查詢 (%d)",
              "0": "複合查詢"
            },
            "value": "內容"
          },
          "editor": {
            "close": "關閉",
            "create": {
              "button": "新增",
              "title": "建立新項目",
              "submit": "建立"
            },
            "edit": {
              "button": "編輯",
              "title": "編輯項目",
              "submit": "更新"
            },
            "remove": {
              "button": "刪除",
              "title": "刪除",
              "submit": "刪除",
              "confirm": {
                "_": "您確定要刪除 %d 筆資料嗎？",
                "1": "您確定要刪除 %d 筆資料嗎？"
              }
            },
            "multi": {
              "restore": "回復修改",
              "title": "每行有不同的價值",
              "info": "您選擇了多個項目，每項目都有不同的價值。如果您想所有選擇的項目都用同一個價值，可以在這裏輸入一個價值。要不然它們會保留原本各自的價值",
              "noMulti": "此列不容許同時編輯多個項目"
            },
            "error": {
              "system": "系統發生錯誤(更多資訊)"
            }
          },
          "autoFill": {
            "cancel": "取消"
          },
          "buttons": {
            "copySuccess": {
              "_": "複製了 %d 筆資料",
              "1": "複製了 1 筆資料"
            },
            "copyTitle": "已經複製到剪貼簿",
            "excel": "Excel",
            "pdf": "PDF",
            "print": "列印",
            "copy": "複製"
          },
          "searchPanes": {
            "collapse": {
              "_": "搜尋面版 (%d)",
              "0": "搜尋面版"
            },
            "emptyPanes": "沒搜尋面版",
            "loadMessage": "載入搜尋面版中...",
            "clearMessage": "清空"
          },
          "select": {
            "rows": {
              "_": "%d 列已選擇",
              "1": "%d 列已選擇"
            }
          }
        }
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      // Setup - add a text input to each footer cell
      $('#qtable tfoot th').each(function(i) {
        var title = $('#qtable thead th').eq($(this).index()).text();
        $(this).html('<input type="text" placeholder="' + title + '" data-index="' + i + '" />');
      });
      var table = $('#qtable').DataTable();
      // Filter event handler
      $(table.table().container()).on('keyup', 'tfoot input', function() {
        table
          .column($(this).data('index'))
          .search(this.value)
          .draw();
      });
    });
  </script>
</body>

</html>