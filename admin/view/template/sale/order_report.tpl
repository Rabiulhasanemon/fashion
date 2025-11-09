<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>PC</Author>
  <LastAuthor>PC</LastAuthor>
  <Created>2020-04-19T15:09:00Z</Created>
  <Version>15.00</Version>
 </DocumentProperties>
 <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office">
  <AllowPNG/>
 </OfficeDocumentSettings>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>12585</WindowHeight>
  <WindowWidth>28800</WindowWidth>
  <WindowTopX>0</WindowTopX>
  <WindowTopY>0</WindowTopY>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="s62">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"
    ss:Bold="1"/>
   <Interior ss:Color="#D0CECE" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s64">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
  </Style>
  <Style ss:ID="s67">
   <Alignment ss:Vertical="Bottom" ss:WrapText="1"/>
  </Style>
  <Style ss:ID="s69">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <NumberFormat ss:Format="Short Date"/>
  </Style>
  <Style ss:ID="s70">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s72">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
   <NumberFormat ss:Format="Short Date"/>
  </Style>
  <Style ss:ID="s73">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"
    ss:Bold="1"/>
   <Interior ss:Color="#D0CECE" ss:Pattern="Solid"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Sheet1">
  <Table x:FullColumns="1"
   x:FullRows="1" ss:DefaultRowHeight="15">
   <Column ss:AutoFitWidth="0" ss:Width="90.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="100.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="100.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="100.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="150.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="250.5"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="120.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="250.75"/>
   <Row ss:AutoFitHeight="0" ss:StyleID="s62">
    <Cell><Data ss:Type="String">Order ID</Data></Cell>
    <Cell><Data ss:Type="String">Date</Data></Cell>
    <Cell><Data ss:Type="String">Customer</Data></Cell>
    <Cell><Data ss:Type="String">Telephone</Data></Cell>
    <Cell><Data ss:Type="String">Address</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Product</Data></Cell>
    <Cell><Data ss:Type="String">Total</Data></Cell>
    <Cell><Data ss:Type="String">Paid</Data></Cell>
    <Cell><Data ss:Type="String">Status</Data></Cell>
    <Cell><Data ss:Type="String">Assignee</Data></Cell>
    <Cell><Data ss:Type="String">Remarks</Data></Cell>
   </Row>
   <?php foreach($orders as $order) { ?>
   <?php if(count($order['items']) == 1 ) { ?>
   <Row ss:AutoFitHeight="0">
    <Cell ss:StyleID="s70"><Data ss:Type="Number"><?php echo $order['order_id']; ?></Data></Cell>
    <Cell ss:StyleID="s72"><Data ss:Type="String"><?php echo $order['date_added']; ?></Data></Cell>
    <Cell ss:StyleID="s72"><Data ss:Type="String"><?php echo $order['customer']; ?></Data></Cell>
    <Cell ss:StyleID="s72"><Data ss:Type="String"><?php echo $order['telephone']; ?></Data></Cell>
    <Cell ss:StyleID="s72"><Data ss:Type="String"><?php echo $order['shipping_address']; ?></Data></Cell>
    <Cell ss:StyleID="s67"><Data ss:Type="String"><?php echo $order['items'][0]['name']; ?></Data></Cell>
    <Cell ss:StyleID="s72"><Data ss:Type="String"><?php echo $order['total']; ?></Data></Cell>
    <Cell ss:StyleID="s72"><Data ss:Type="String"><?php echo $order['amount_paid']; ?></Data></Cell>
    <Cell ss:StyleID="s72"><Data ss:Type="String"><?php echo $order['status']; ?></Data></Cell>
    <Cell ss:StyleID="s72"><Data ss:Type="String"><?php echo $order['assignee']; ?></Data></Cell>
    <Cell ss:StyleID="s72"><Data ss:Type="String"><?php echo $order['comment']; ?></Data></Cell>
   </Row>
   <?php } else { ?>
   <?php foreach($order['items'] as $i => $product) { ?>
   <?php if($i == 0) { ?>
    <Row ss:AutoFitHeight="0">
    <Cell ss:MergeDown="<?php echo count($order['items']) - 1; ?>" ss:StyleID="s64"><Data ss:Type="Number"><?php echo $order['order_id']; ?></Data></Cell>
    <Cell ss:MergeDown="<?php echo count($order['items']) - 1; ?>" ss:StyleID="s69"><Data ss:Type="String"><?php echo $order['date_added']; ?></Data></Cell>
    <Cell ss:MergeDown="<?php echo count($order['items']) - 1; ?>" ss:StyleID="s69"><Data ss:Type="String"><?php echo $order['customer']; ?></Data></Cell>
    <Cell ss:MergeDown="<?php echo count($order['items']) - 1; ?>" ss:StyleID="s69"><Data ss:Type="String"><?php echo $order['telephone']; ?></Data></Cell>
    <Cell ss:MergeDown="<?php echo count($order['items']) - 1; ?>" ss:StyleID="s69"><Data ss:Type="String"><?php echo $order['shipping_address']; ?></Data></Cell>
    <Cell ss:StyleID="s67"><Data ss:Type="String"><?php echo $product['name']; ?></Data></Cell>
    <Cell ss:MergeDown="<?php echo count($order['items']) - 1; ?>" ss:StyleID="s69"><Data ss:Type="String"><?php echo $order['total']; ?></Data></Cell>
    <Cell ss:MergeDown="<?php echo count($order['items']) - 1; ?>" ss:StyleID="s69"><Data ss:Type="String"><?php echo $order['amount_paid']; ?></Data></Cell>
    <Cell ss:MergeDown="<?php echo count($order['items']) - 1; ?>" ss:StyleID="s69"><Data ss:Type="String"><?php echo $order['status']; ?></Data></Cell>
    <Cell ss:MergeDown="<?php echo count($order['items']) - 1; ?>" ss:StyleID="s69"><Data ss:Type="String"><?php echo $order['assignee']; ?></Data></Cell>
    <Cell ss:MergeDown="<?php echo count($order['items']) - 1; ?>" ss:StyleID="s69"><Data ss:Type="String"><?php echo $order['comment']; ?></Data></Cell>
   </Row>
   <?php } else { ?>
   <Row ss:AutoFitHeight="0">
    <Cell ss:Index="6" ss:StyleID="s67"><Data ss:Type="String"><?php echo $product['name']; ?></Data></Cell>
   </Row>
   <?php } ?>
   <?php } ?>
   <?php } ?>
  <?php } ?>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Header x:Margin="0.3"/>
    <Footer x:Margin="0.3"/>
    <PageMargins x:Bottom="0.75" x:Left="0.7" x:Right="0.7" x:Top="0.75"/>
   </PageSetup>
   <Unsynced/>
   <Print>
    <ValidPrinterInfo/>
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>600</VerticalResolution>
   </Print>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveCol>2</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>
