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
        <th scope="col">Waktu</th>
        <th scope="col">No. PO/SPK</th>
        <th scope="col">Penyedia Barang</th>
        <th scope="col">Grand Total(Rp)</th>
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

            $tot_grand_total+=$dt->grand_total;
            echo '<td class="center">'.$no++.'</td>';
            echo '<td class="center">'. $dt->waktu.'</td>';
            echo '<td class="center">'. $dt->no_transaksi.'</td>';
            echo '<td class="center">'. $dt->vendor.$dt->vendor_lainnya.'</td>';
            echo '<td class="right">'. Formatter::toRp($dt->grand_total).'</td>';
            echo '</tr>';
        }
        echo '<tr class="oddrow">';
        echo '<th colspan="4" class="center">Total</th>';
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

