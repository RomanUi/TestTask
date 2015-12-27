<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Top 250</title>

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="https://code.jquery.com/jquery-2.1.4.min.js" type="text/javascript"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="js/jquery.blockUI.js"></script>
    <script type="text/javascript">
        $( document).ready(function() {
            $.blockUI.defaults.message = '<img src="img/progress.gif" />';

            $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
            data.load();
        });

        var data = {
            offset: 0,
            limit: 10,
            currentPage: 1,
            dataCount: 0,
            pageItemsCount: 10,
            dataSource: '/movies',
            table: $('.mainData tbody'),
            load: function() {
                var that = this;
                var offset = this.offset === undefined ? 0 : this.offset;
                var limit = this.limit === undefined ? 10 : this.limit;

                $.get(this.dataSource + '?limit=' + limit + '&offset=' + offset, function(data, status) {
                    if(status === 'success') {
                        that.countPage = Math.ceil(data.total/that.limit);
                        that.initialPagination();
                        data.list.forEach(function(item) {
                            that.appendItem(item);
                        });
                    } else {
                        alert('Some error. Please try again latter.');
                    }
                });
            },
            nextPage: function(e) {
                if(this.countPage == this.currentPage) {
                    return false;
                }

                this.setPage(this.currentPage + 1);
            },
            prewPage: function() {
                if(this.currentPage == 1) {
                    return false;
                }

                this.setPage(this.currentPage - 1);
            },
            setPage: function(page) {
                page = parseInt(page);
                this.destroy();
                this.offset = this.limit * (page-1);
                this.load();
                this.currentPage = page;
            },
            appendItem: function(item) {
                $('#mainData tbody').append(
                    '<tr>' +
                        '<td>' + item.position + '</td>' +
                        '<td>' + item.title + '</td>' +
                        '<td>' + item.year + '</td>' +
                        '<td>' + item.rating + '</td>' +
                        '<td>' + item.num_votes + '</td>' +
                    '<tr>'
                );
            },
            initialPagination: function() {
                var paginationInner = '<li class="left"><a onclick="data.prewPage()" href="javascript:void(null)" aria-label="Previous">&laquo;</a></li>';

                for(var i = 1; i <= this.countPage; i++) {
                    paginationInner += '<li ' + (i === this.currentPage ? 'class="active"':'') + '><a onclick="data.setPage(this.text)" href="javascript:void(null)">' + i + '</a></li>';
                }

                paginationInner += '<li class="right"><a onclick="data.nextPage()" href="javascript:void(null)" aria-label="Next">&raquo;</span></a></li>';

                $('.pagination').append(paginationInner);
            },
            destroy: function() {
                $('#mainData tbody tr').remove();
                $('.pagination li').remove();
            }
        } 
    </script>
    </head>
    <body id="page-top" class="index">
        <!-- Navigation -->
        <nav class="navbar navbar-default">
            <div class="container">
                <a class="navbar-brand" href="/">IMDB top 250</a>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <table id='mainData' class="table table-striped">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Title</td>
                            <td>Year</td>
                            <td>Rating</td>
                            <td>Num Votes</td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <nav>
            <ul class="pagination"></ul>
        </nav>
    </body>
</html>
