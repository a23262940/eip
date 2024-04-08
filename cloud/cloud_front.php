<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <!--html head-->
  <?php include '../php/html_head.php'; ?>
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
      <!--雲端-整體頁面-->
      <main class="col-lg-10 px-md-5">
        <!--雲端-搜尋區塊-->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">雲端資料庫</h1>
          <div class="justify-content-start">目前位置:我的雲端>我家</div>
          <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
              <input class="form-control" placeholder="搜尋..." required>
              <button type="button" class="btn btn-sm btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                </svg>
              </button>
            </div>
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle btn-info" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cloud-plus" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z" />
                  <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                </svg>
                新增
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li>
                  <a class="dropdown-item" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-up" viewBox="0 0 16 16">
                      <path d="M8.5 11.5a.5.5 0 0 1-1 0V7.707L6.354 8.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 7.707V11.5z" />
                      <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                    </svg>
                    檔案上傳
                  </a>
                </li>
                <li>
                  <a class="dropdown-item border-bottom" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-arrow-up" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z" />
                      <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                    </svg>
                    資料夾上傳
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder-plus" viewBox="0 0 16 16">
                      <path d="m.5 3 .04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2zm5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19c-.24 0-.47.042-.683.12L1.5 2.98a1 1 0 0 1 1-.98h3.672z" />
                      <path d="M13.5 10a.5.5 0 0 1 .5.5V12h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V13h-1.5a.5.5 0 0 1 0-1H13v-1.5a.5.5 0 0 1 .5-.5z" />
                    </svg>
                    建立資料夾
                  </a>
                </li>
              </ul>
            </div>

          </div>
        </div>

        <!--所有委任-列表區塊 + 分頁導航-->
        <div class="table-responsive">
          <table class="table table-hover table-sm">
            <thead>
              <tr class="info">
                <th>名稱</th>
                <th>標籤</th>
                <th>權限</th>
                <th>上傳日期</th>
                <th>檔案大小</th>
                <th>其他</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                    <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z" />
                  </svg>
                  jpg圖標
                </td>
                <td class="table-primary">運送至台中市政府</td>
                <td class="table-secondary">運送任務</td>
                <td class="table-success">台中市政府</td>
                <td class="table-danger">50%</td>
                <th>
                  <button class="btn btn-success">下載</button>
                  <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#revise-Modal">修改</button>
                  <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#dum-delete-Modal">刪除</button>
                </th>
              </tr>
              <tr>
                <td>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-font" viewBox="0 0 16 16">
                    <path d="M10.943 6H5.057L5 8h.5c.18-1.096.356-1.192 1.694-1.235l.293-.01v5.09c0 .47-.1.582-.898.655v.5H9.41v-.5c-.803-.073-.903-.184-.903-.654V6.755l.298.01c1.338.043 1.514.14 1.694 1.235h.5l-.057-2z" />
                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z" />
                  </svg>
                  text圖標
                </td>
                <td class="table-warning">運送至台中市政府</td>
                <td class="table-info">運送任務</td>
                <td class="table-light">台中市政府</td>
                <td class="table-dark">0%</td>
                <th>
                  <button class="btn btn-success">下載</button>
                  <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#revise-Modal">修改</button>
                  <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#dum-delete-Modal">刪除</button>
                </th>
              </tr>
              <tr>
                <td>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z" />
                  </svg>
                  file圖標
                </td>
                <td>運送至台中市政府</td>
                <td>運送任務</td>
                <td>台中市政府</td>
                <td>0%</td>
                <th>
                  <button class="btn btn-success">下載</button>
                  <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#revise-Modal">修改</button>
                  <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#dum-delete-Modal">刪除</button>
                </th>
              </tr>
              <tr>
                <td>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder" viewBox="0 0 16 16">
                    <path d="M.54 3.87.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.826a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31zM2.19 4a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4H2.19zm4.69-1.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707z" />
                  </svg>
                  資料夾圖標
                </td>
                <td>運送至台中市政府</td>
                <td>運送任務</td>
                <td>台中市政府</td>
                <td>0%</td>
                <th>
                  <button class="btn btn-success">下載</button>
                  <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#revise-Modal">修改</button>
                  <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#dum-delete-Modal">刪除</button>
                </th>
              </tr>
              <tr>
                <td>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-zip" viewBox="0 0 16 16">
                    <path d="M6.5 7.5a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v.938l.4 1.599a1 1 0 0 1-.416 1.074l-.93.62a1 1 0 0 1-1.109 0l-.93-.62a1 1 0 0 1-.415-1.074l.4-1.599V7.5zm2 0h-1v.938a1 1 0 0 1-.03.243l-.4 1.598.93.62.93-.62-.4-1.598a1 1 0 0 1-.03-.243V7.5z" />
                    <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm5.5-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9v1H8v1h1v1H8v1h1v1H7.5V5h-1V4h1V3h-1V2h1V1z" />
                  </svg>
                  壓縮檔案圖標
                </td>
                <td>運送至台中市政府</td>
                <td>運送任務</td>
                <td>台中市政府</td>
                <td>0%</td>
                <th>
                  <button class="btn btn-success">下載</button>
                  <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#revise-Modal">修改</button>
                  <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#dum-delete-Modal">刪除</button>
                </th>
              </tr>
            </tbody>
          </table>
        </div>

      </main>

    </div>
  </div>

  <!-- footer copyright-->
  <?php include '../php/footer.php'; ?>

  <!--防呆警告 模态框（Modal)-->
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

  <!--修改檔案 模态框（Modal)-->
  <div class="modal fade" id="revise-Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
          </svg>
          <h5 class="modal-title">編輯資料</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="form-group row">
              <label class="col-sm-3 control-label p-2">檔案名稱:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" placeholder="原本叫的名字.jpg" required>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label p-2">標籤:</label>
              <div class="col-sm-9">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="自行輸入..." aria-label="Text input with dropdown button">
                  <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">基本分類</button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">A</a></li>
                    <li><a class="dropdown-item" href="#">B</a></li>
                    <li><a class="dropdown-item" href="#">C</a></li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#">???</a></li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 control-label p-2">權限:</label>
              <div class="col-sm-9">
                <select type="cost" class="form-select" id="country" required>
                  <option value="">選擇...</option>
                  <option>A</option>
                  <option>B</option>
                </select>
              </div>
            </div>

            <div class="modal-footer">
              <div class="form-group">
                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary">確定</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>

</html>