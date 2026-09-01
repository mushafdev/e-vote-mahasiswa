<table style="width:100%;margin-bottom:10px;">
    <tr>
        {{-- <td width="12%">
        </td> --}}
        <td>
            <table>
                <tr>
                    <td><b>Unit Kerja</b></td>
                    <td>{{$unit_kerja->unit_kerja}}</td>
                </tr>
                <tr>
                    <td><b>Nama Barang</b></td>
                    <td>{{$detail_barang->barang}}</td>
                </tr>
                <tr>
                    <td><b>Kode Barang</b></td>
                    <td>{{$detail_barang->kode}}</td>
                </tr>
                <tr>
                    <td><b>Satuan</b></td>
                    <td>{{$detail_barang->satuan}}</td>
                </tr>
            </table>
            <p></p>
        </td>
        <td style="text-align:right;">
            <h2 class="grey">{{Formatter::identity()['title']}}</h2>
            <p>{{$unit_kerja->alamat}}</p>
            <h2 style="color: #2796f7;text-transform:uppercase"><?=$title?></h2>
            <h4 class="grey"><?=$subtitle?></h4>
        </td>
    </tr>
</table>
<table class="bpmTopic">
<thead>
    <tr class="oddrow">
        <th scope="col">Waktu</th>
        <th scope="col">No. Transaksi</th>
        <th scope="col">Status Request</th>
        <th scope="col">Unit Kerja</th>
        <th scope="col">Harga</th>
        <th scope="col">Qty</th>
        <th scope="col">Total</th>
    </tr>
</thead>
<tbody>
    <?php
    $no=1;
    $tot_qty=0;
    $tot_sub_total=0;
    if($q->count()>0){
        foreach($q as $dt){
            $status_order=array(0=>'Menunggu',1=>'Approve',2=>'Backorder',3=>'Ditolak',4=>'Dikirim',5=>'Diterima');
            $status_order_color=array(0=>'warning',1=>'success',2=>'info',3=>'danger',4=>'alternate',5=>'primary');               
            $tot_qty+=$dt->qty_accept;
            $tot_sub_total+=$dt->sub_total;
            echo '<tr>';
            echo '<td>'.Formatter::datetime($dt->waktu).'</td>';
            echo '<td>'.$dt->no_transaksi.'</td>';
            echo '<td class="center">'.'<span class="badge badge-'.$status_order_color[$dt->status].'">'.$status_order[$dt->status].'</span>'.'</td>';
            echo '<td>'.$dt->unit_kerja.'</td>';
            echo '<td class="right">'.Formatter::toRp($dt->harga).'</td>';
            echo '<td class="center">'.$dt->qty_accept.'</td>';
            echo '<td class="right">'.Formatter::toRp($dt->sub_total).'</td>';
            echo '</tr>';
        }
        echo '<tr class="alert alert-success">';
        echo '<th class="center" colspan="5"></th>';
        echo '<th class="center">'.$tot_qty.'</th>';
        echo '<th class="right">'.Formatter::toRp($tot_sub_total).'</th>';
        echo '</tr>';
    }else{
        echo '<tr>';
        echo '<td class="center text-danger" colspan="12">Data Kosong</td>';
        echo '</tr>';
    }

    ?>
</tbody>
</table>

