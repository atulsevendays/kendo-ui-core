﻿<?php
require_once '../lib/Kendo/Autoload.php';
require_once '../include/header.php';
?>
<div role="application">
    <div class="demo-section k-content">
<?php
    $listbox1 = new \Kendo\UI\ListBox('discontinued');

    $listbox1->dataValueField("ProductID")
             ->dataTextField("ProductName")
             ->draggable(true)
             ->dropSources(array("available"))
             ->connectWith("available")
             ->selectable("Single");

    $listbox1->addEvent("function(e){ setDiscontinued(e, true)}")
             ->remove("function(e){ setDiscontinued(e, false)}");

    echo $listbox1->render();
?>
        <span class="k-icon k-i-redo"></span>
        <span class="k-icon k-i-redo flipped"></span>
<?php
    $listbox2 = new \Kendo\UI\ListBox('available');

    $listbox2->dataValueField("ProductID")
             ->dataTextField("ProductName")
             ->draggable(true)
             ->dropSources(array("discontinued"))
             ->connectWith("discontinued")
             ->selectable("single");

    echo $listbox2->render();
?>
        <button id="save-changes-btn">Save changes</button>
    </div>
</div>

<script>
    var crudServiceBaseUrl = "https://demos.telerik.com/kendo-ui/service",
        dataSource;

    $(document).ready(function () {
            dataSource = new kendo.data.DataSource({
                serverFiltering: false,
                transport: {
                    read: {
                        url: crudServiceBaseUrl + "/Products",
                        dataType: "jsonp"
                    },
                    update: {
                        url: crudServiceBaseUrl + "/Products/Update",
                        dataType: "jsonp"
                    },
                    parameterMap: function (options, operation) {
                        if (operation !== "read" && options.models) {
                            return { models: kendo.stringify(options.models) };
                        }
                    }
                },
                batch: true,
                schema: {
                    model: {
                        id: "ProductID",
                        fields: {
                            ProductID: { editable: false, nullable: true },
                            Discontinued: { type: "boolean" },
                        }
                    }
                }
            });

        dataSource.fetch(function () {
            var data = this.data();
            var discontinued = $("#discontinued").data("kendoListBox");
            var available = $("#available").data("kendoListBox");

            for (var i = 0; i < data.length; i++) {
                if (data[i].Discontinued) {
                    discontinued.add(data[i]);
                }
                else {
                    available.add(data[i]);
                }
            }
        });

        $("#save-changes-btn").kendoButton({
            click: function (e) {
                dataSource.sync();
            }
        });
    });

    function setDiscontinued(e, flag) {
        var removedItems = e.dataItems;
        for (var i = 0; i < removedItems.length; i++) {
            var item = dataSource.get(removedItems[i].ProductID);
            item.Discontinued = flag;
            item.dirty = !item.dirty;
        }
    }
</script>

<style>
    #save-changes-btn {
        float: right;
        margin-top: 20px;
    }

    #example .demo-section {
        max-width: none;
        width: 555px;
    }

    #example .k-listbox {
        width: 255px;
        height: 310px;
    }

    #example .k-i-redo {
        margin-bottom: 10px;
        opacity: 0.5;
    }

    #example .k-i-redo:hover {
        color: inherit !important;
    }

    #example .flipped {
        -webkit-transform: rotate(180deg);
        -moz-transform: rotate(180deg);
        -o-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        transform: rotate(180deg);
        margin-top: 30px;
        margin-right: 1px;
    }
</style>
<?php require_once '../include/footer.php'; ?>
