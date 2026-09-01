<table style="width:100%;margin-bottom:10px;">
    <tr>
        {{-- <td width="12%">
        </td> --}}
        <td>

            <h2 class="grey">{{Formatter::identity()['title']}}</h2>
            <p><?=$title_add?></p>
            <h2 style="color: #2796f7;text-transform:uppercase"><?=$title?></h2>
            <h4 class="grey"><?=$subtitle?></h4>
            <p></p>
        </td>
        <td style="text-align:right;">
        </td>
    </tr>
</table>
<table class="bpmTopic">
<thead>
    <tr class="oddrow">
        <th scope="col" class="text-center">No</th>
        <th scope="col">Kode</th>
        <th scope="col">Nama Barang</th>
        <th scope="col">Qty</th>
        <th scope="col">Satuan</th>
        <th scope="col">Kategori</th>
        <th scope="col">Sub Kategori</th>
        <th scope="col">Total(Rp)</th>
    </tr>
</thead>
<tbody>
    <?php
    $no=1;
    $cek_row=$q->count();
    if($cek_row>0){
        $tot_grand_total=0;
        foreach($q as $dt){
            echo '<tr>';

            $tot_grand_total+=$dt->total;
            echo '<td class="center">'.$no++.'</td>';
            echo '<td class="center">'. $dt->kode.'</td>';
            echo '<td class="center">'. $dt->barang.'</td>';
            echo '<td class="center">'. $dt->qty.'</td>';
            echo '<td class="center">'. $dt->satuan.'</td>';
            echo '<td class="center">'. $dt->kategori_barang.'</td>';
            echo '<td class="center">'. $dt->sub_kategori_barang.'</td>';
            echo '<td class="right">'. Formatter::toRp($dt->total).'</td>';
            echo '</tr>';
        }
        echo '<tr class="oddrow">';
        echo '<th colspan="7" class="center">Total</th>';
        echo '<th class="right">'.Formatter::toRp($tot_grand_total).'</th>';
        echo '</tr>';
    }else{
        echo '<tr>';
        echo '<td class="center" colspan="12"><i>Data Kosong</i></td>';
        echo '</tr>';
    }

    ?>
</tbody>
</table>

