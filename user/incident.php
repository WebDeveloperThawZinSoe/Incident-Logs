<?php
            include "head.php";
            include "alert.php";
        ?>
<div class="d-flex" id="wrapper">
    <!-- Sidebar-->
    <?php
                include "slidebar.php";
           ?>
    <!-- Page content wrapper-->
    <div id="page-content-wrapper">
        <!-- Top navigation-->
        <?php
                    include "nav.php";
                ?>
        <!-- Page content-->
        <div class="container-fluid">
            <br>

            <!-- Category -->



            <?php
                $sub_id = htmlspecialchars($_GET['sub_id']);
                $cat_id = htmlspecialchars($_GET["cat_id"]) ;
                $sql = "SELECT * FROM category WHERE id=$cat_id";
                $result = mysqli_query($connect,$sql);
                if($result){
                    foreach($result as $row){
                        $id = $row["id"];
                        $name = $row["category"];
                        $sql2 = "SELECT * FROM sub_category WHERE id=$sub_id";
                        $result2 = mysqli_query($connect,$sql2);
                        if($result2){
                            foreach($result2 as $row2){
                               
                                $subcategory = $row2["subcategory"];
                                ?>
            <div class="row">

                <div class="container">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="category.php">Category</a></li>
                            <li class="breadcrumb-item"><a
                                    href="subcategory.php?id=<?php echo $cat_id ?>"><?php echo $name ?></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo $subcategory ?></a></li>

                        </ol>
                    </nav>
                </div>


            </div>
            <?php
                            }
                        }
                        
                    }
                }

            ?>

            <!-- Sub Category -->


            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Soultion Count</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $sql = "SELECT * FROM incident WHERE cat_id=$cat_id && sub_cat_id=$sub_id";
                                $result = mysqli_query($connect,$sql);
                                $number_row = mysqli_num_rows($result);
                                $number = 0;
                                $number += $number;
                                if($number_row > 0){
                                    foreach($result as $key=>$value){
                                      
                                        
                                        ?>
                        <tr>
                            <td><?php echo ++$number; ?></td>
                            <td>
                                <a
                                    href="solution.php?cat_id=<?php echo $cat_id; ?>&&sub_id=<?php echo $sub_id ?>&&incident_id=<?php echo $value['id']; ?>">
                                    <?php echo $value['title']; ?> </a>

                            </td>
                            <td>
                                <?php
                                        $sql2 = "SELECT * FROM solution WHERE incident_id=$id";
                                        $result2 = mysqli_query($connect,$sql2);
                                        $count = mysqli_num_rows($result2);
                                        
                                    ?>
                                <button type="button" class="btn btn-primary">
                                    <?php
echo $count;
?>
                                </button>
                            </td>
                            
                        </tr>
                        <?php
                                        }
                                    }
                                
                            ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>



<?php
            include "footer.php";
        ?>