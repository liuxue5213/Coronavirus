<!-- Modal -->
<div class="modal fade" id="Subscribe" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle"><b>订阅</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2>Get Info For Coronavirus</h2>
                <div id="remind" class="alert alert-danger" role="alert" style="display: none;">
                    please input email
                </div>
                <div class="form-group">
                    <label for="email">Email Address <span style="color: red">*</span></label><br>
                    <input id="email" class="form-control" type="email" onchange="checkEmail()">
                </div>
                <div class="form-group">
                    <label for="name">Name</label><br>
                    <input id="name" class="form-control" type="text">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="sub" type="button" class="btn btn-primary" onclick="sub()">Subscribe</button>
            </div>
        </div>
    </div>
</div>