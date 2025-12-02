@php
    // month and year coming from your $months array
    $firstDate = \Carbon\Carbon::create($year, $month, 1)->format('d.m.Y');
    $lastDate  = \Carbon\Carbon::create($year, $month, 1)->endOfMonth()->format('d.m.Y');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

       <style type="text/css">
        /* --------------------- YOUR EXISTING STYLES --------------------- */
        .ritz .waffle a {
            color: inherit;
        }

        .ritz .waffle .s9 {
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: Verdana;
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 0px 3px 0px 3px;
        }

        .ritz .waffle .s2 {
            border-bottom: 1px SOLID transparent;
            border-right: 1px SOLID transparent;
            background-color: #ffff00;
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            text-decoration-skip-ink: none;
            -webkit-text-decoration-skip: none;
            color: #000000;
            font-family: Verdana;
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 0px 3px 0px 3px;
        }

        .ritz .waffle .s7 {
            background-color: #ffffff;
            text-align: end;
            color: #000000;
            font-family: Verdana;
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 0px 3px 0px 3px;
        }

        .ritz .waffle .s8 {
            background-color: #ffffff;
            text-align: left;
            font-weight: bold;
            text-decoration: underline;
            text-decoration-skip-ink: none;
            -webkit-text-decoration-skip: none;
            color: #000000;
            font-family: Verdana;
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 0px 3px 0px 3px;
        }

        .ritz .waffle .s1 {
            border-bottom: 1px SOLID transparent;
            border-right: 1px SOLID transparent;
            background-color: #ffff00;
            text-align: center;
            color: #000000;
            font-family: Verdana;
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 0px 3px 0px 3px;
        }

        .ritz .waffle .s10 {
            background-color: #ffffff;
            text-align: right;
            color: #000000;
            font-family: Verdana;
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 0px 3px 0px 3px;
        }

        .ritz .waffle .s3 {
            background-color: #ffffff;
            /* text-align: center; */
            /* font-weight: bold; */
            /* text-decoration: underline; */
            text-decoration-skip-ink: none;
            -webkit-text-decoration-skip: none;
            color: #000000;
            font-family: Verdana;
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 0px 3px 0px 3px;
        }

        .ritz .waffle .s11 {
            border-bottom: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: right;
            color: #000000;
            font-family: Verdana;
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 0px 3px 0px 3px;
        }

        .ritz .waffle .s12 {
            border-bottom: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: Verdana;
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 0px 3px 0px 3px;
        }

        .ritz .waffle .s13 {
            border-bottom: 2px SOLID #000000;
            background-color: #ffffff;
            text-align: right;
            color: #000000;
            font-family: Verdana;
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 0px 3px 0px 3px;
        }

        .ritz .waffle .s4 {
            border-bottom: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            text-decoration-skip-ink: none;
            -webkit-text-decoration-skip: none;
            color: #000000;
            font-family: Verdana;
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 0px 3px 0px 3px;
        }

        .ritz .waffle .s0 {
            border-bottom: 1px SOLID transparent;
            border-right: 1px SOLID transparent;
            background-color: #ffff00;
            text-align: center;
            font-weight: bold;
            color: #000000;
            font-family: Verdana;
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 0px 3px 0px 3px;
        }

        .ritz .waffle .s6 {
            border: unset;
            /* border-right: 1px SOLID #000000; */
            background-color: #ffffff;
            text-align: center;
            /* font-weight: bold; */
            color: #000000;
            font-family: Verdana;
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 0px 3px 0px 3px;
        }

        .ritz .waffle .s5 {
            border: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: Verdana;
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 0px 3px 0px 3px;
        }

        /* --------------------- PRINT STYLES (A4 Portrait) --------------------- */
        @page {
            size: A4 portrait;
            margin: 10mm 18mm 10mm 25mm;
        }

        @media print {

            html,
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Hide everything except printable content */
            body * {
                visibility: hidden;
            }

            .grid-container,
            .grid-container * {
                visibility: visible;
            }

            .grid-container {
                position: relative;
                left: 0;
                top: 0;
                width: 100%;
                transform-origin: top left;
                /* The scale will only apply if everything fits in one page */
            }

            /* === For Multiple Tables === */
            .grid-container table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10mm;
                /* space between tables */
                page-break-inside: avoid;
                /* prevent table split */
                page-break-after: auto;
                /* allow flowing to next page */
            }

            .grid-container table:last-of-type {
                margin-bottom: 0;
            }

            .waffle td,
            .waffle th {
                padding: 3px;
                /* keep table borders visible if you like */
                /* border: 1px solid #000; */
            }

            .s0,
            .s1,
            .s2,
            .s4 {
                text-align: center !important;
            }

            /* Automatic scale adjustment (only if all tables fit in one page) */
            .auto-scale {
                transform-origin: top center;
                transform: scale(1);
            }

            footer {
                display: block;
                position: fixed;
                bottom: 3mm;
                left: 0;
                width: 100%;
                text-align: center;
                font-size: 8pt;
                color: #555;
            }
        }



        .top table tbody .s6 {

            border: 1px SOLID #000000 !important;
            font-weight: bold;

        }
         /* Centering wrapper */
    .container {
        width: 100%;
        margin: 0 auto;
    }
    </style>

   
<style>
    @page {
        size: A4;
       margin: 10mm 18mm 10mm 25mm;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed; /* prevents page overflow */
    }

    td, th {
        padding: 4px 6px;
        vertical-align: top;
        word-wrap: break-word;
    }

    /* Optional: set fixed column widths to prevent stretching */
    .col-25 { width: 25px; }
    .col-270 { width: 270px; }
    .col-109 { width: 109px; }
    .col-116 { width: 116px; }

    /* Force page break where needed */
    .page-break {
        page-break-before: always;
    }

   
</style>


</head>

<body>

 
    <div class="container">

        <div class="ritz grid-container auto-scale top" dir="ltr">
            <table class="waffle" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <!-- <th class="row-header freezebar-origin-ltr"></th> -->
                        <th id="1345355025C0" style="width:25px;" class="column-headers-background"></th>
                        <th id="1345355025C1" style="width:270px;" class="column-headers-background"></th>
                        <th id="1345355025C2" style="width:109px;" class="column-headers-background"></th>
                        <th id="1345355025C3" style="width:116px;" class="column-headers-background"></th>
                        <th id="1345355025C4" style="width:25px;" class="column-headers-background"></th>
                        <th id="1345355025C5" style="width:270px;" class="column-headers-background"></th>
                        <th id="1345355025C6" style="width:109px;" class="column-headers-background"></th>
                        <th id="1345355025C7" style="width:116px;" class="column-headers-background"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="height: 19px">

                        <td class="s0" colspan="8">{{ $appUser->app_u_name }}</td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s0" colspan="8">CIN No - {{ $appUser->cin_no }}</td>
                    </tr>

                    <tr style="height: 19px">

                        <td class="s1" colspan="8">{{ $appUser->app_u_address ?? 'NA' }}</td>
                        {{-- <td class="s1" colspan="8">Vill/Town:41/B Haripada Lane , Udayan Apartment Flat no-5</td> --}}
                    </tr>
                    <tr style="height: 19px">

                        <td class="s2" colspan="8">P.S: {{ $appUser->police_station ?? 'NA' }}, Dist:
                            {{ $appUser->user_district ?? 'NA' }}, Pin: {{ $appUser->pin_code ?? 'NA' }},
                            {{ $appUser->user_state ?? 'NA' }}</td>
                    </tr>
                    <tr style="height: 9px">

                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s2" colspan="8" style=" text-decoration: none; ">Mob No -
                            {{ $appUser->phone_number ?? 'NA' }} | Email Id - {{ $appUser->user_email ?? 'NA' }}</td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s4" colspan="8">
                            Receipt & Payment Account For The Year {{ $firstDate }} to {{ $lastDate }}
                            
                        </td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s5" colspan="2">Receipts</td>
                        <td class="s6">Amount</td>
                        <td class="s6">Amount</td>
                        <td class="s5" colspan="2">Payamnet</td>
                        <td class="s6">Amount</td>
                        <td class="s6">Amount</td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s7">To</td>
                        <td class="s8">Opening Balance</td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s7">By</td>
                        <td class="s9">Daily Collection Loan</td>
                        <td class="s10"> {{ number_format($daily_collection_loan, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s7"></td>
                        <td class="s9">Cash In Hand</td>
                        <td class="s10"> {{ number_format($opening_cash_in_hand, 2) }} </td>
                        <td class="s7"></td>
                        <td class="s7">By</td>
                        <td class="s9">Weekly Collection Loan</td>
                        <td class="s10">{{ number_format($weekly_collection_loan, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s7"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s7"></td>
                        <td class="s9">By</td>
                        <td class="s9">Biweekly Collection Loan</td>
                        <td class="s10"> {{ number_format($bi_weekly_collection_loan, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s7"></td>
                        <td class="s9">Indian Bank, Kolkata Ballygunge Br.</td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9">By</td>
                        <td class="s9">Monthly Loan</td>
                        <td class="s10"> {{ number_format($monthly_collection_loan, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s9"></td>
                        <td class="s9">A/C No.CA:7815227267</td>
                        <td class="s11"> {{ number_format($opening_cash_in_bank, 2) }} </td>
                        <td class="s9"></td>
                        <td class="s9">By</td>
                        <td class="s9">Fund Saving Withdraw </td>
                        <td class="s10"> {{ number_format($fund_saving_withdraw, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s10"> {{ number_format($total_opening_balance, 2) }} </td>
                        <td class="s9">By</td>
                        <td class="s9">Interest paid on Fund Saving Amount</td>
                        <td class="s10"> {{ number_format($total_rd_interest, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9">By</td>
                        <td class="s9">Interest Paid on Loan Taken</td>
                        <td class="s10"> {{ number_format($interest_paid_on_loan, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s7">To</td>
                        <td class="s9">Credit EMI</td>
                        <td class="s10">{{ number_format($toCreditEMI, 2) }}</td>
                        <td class="s9"></td>
                        <td class="s9">By</td>
                        <td class="s9">Other Charges Paid for Loan Taken</td>
                        <td class="s10"> {{ number_format($other_charges_paid_for_loan_taken, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s7">To</td>
                        <td class="s9">Short term borrowing</td>
                        <td class="s10"> {{ number_format($short_term_borrowing, 2) }} </td>
                        <td class="s9"></td>
                        <td class="s9">By</td>
                        <td class="s9">Insurance Charge</td>
                        <td class="s10"> {{ number_format($paid_insurance_charge, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s7">To</td>
                        <td class="s9">Long term borrowing</td>
                        <td class="s10"> {{ number_format($long_term_borrowing, 2) }} </td>
                        <td class="s9"></td>
                        <td class="s9">By</td>
                        <td class="s9">Director Salary</td>
                        <td class="s10"> {{ number_format($director_salary, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s7">To</td>
                        <td class="s9">Membership charge</td>
                        <td class="s10"> {{ number_format($membership_charge, 2) }} </td>
                        <td class="s9"></td>
                        <td class="s7">By</td>
                        <td class="s9">Staff Salary</td>
                        <td class="s10"> {{ number_format($staff_salary, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s7">To</td>
                        <td class="s9">Processing charge</td>
                        <td class="s10"> {{ number_format($processing_charge, 2) }}</td>
                        <td class="s9"></td>
                        <td class="s7">By</td>
                        <td class="s9">Staff Uniform & ID Card</td>
                        <td class="s10"> {{ number_format($staff_uniform_id_card, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s7">To</td>
                        <td class="s9">Insurance charge</td>
                        <td class="s10"> {{ number_format($insurance_charge, 2) }} </td>
                        <td class="s9"></td>
                        <td class="s7">By</td>
                        <td class="s9">Staff Training</td>
                        <td class="s10"> {{ number_format($staff_training, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s7">To</td>
                        <td class="s9">Intarest Received on Microfinance Loan</td>
                        <td class="s10"> {{ number_format($IntarestReceivedOnMicrofinanceLoan, 2) }} </td>
                        <td class="s9"></td>
                        <td class="s7">By</td>
                        <td class="s9">Customer Awareness Camp</td>
                        <td class="s10"> {{ number_format($customer_awareness_camp, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s7">To</td>
                        <td class="s9">Fund Saving Amount</td>
                        <td class="s10"> {{ number_format($fund_saving_amount, 2) }} </td>
                        <td class="s9"></td>
                        <td class="s7">By</td>
                        <td class="s9">Cultural Programme</td>
                        <td class="s10"> {{ number_format($cultural_programme, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s7">To</td>
                        <td class="s9">Penalty</td>
                        <td class="s10"> {{ number_format($penalty, 2) }} </td>
                        <td class="s9"></td>
                        <td class="s7">By</td>
                        <td class="s9">Social Welfare Activity</td>
                        <td class="s10"> {{ number_format($social_welfare_activity, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s7">To</td>
                        <td class="s9">Others</td>
                        <td class="s11"> {{ number_format($others, 2) }} </td>
                        <td class="s9"></td>
                        <td class="s7">By</td>
                        <td class="s9">Office Rent</td>
                        <td class="s10"> {{ number_format($office_rent, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s7"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s10"> {{ number_format($leftSideSum, 2) }} </td>
                        <td class="s7">By</td>
                        <td class="s9">Electricity Bill</td>
                        <td class="s10"> {{ number_format($electricity_bill, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s7">By</td>
                        <td class="s9">Mobile Recharge & Wi-Fi Bill</td>
                        <td class="s10"> {{ number_format($internet_mobile_recharge, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s7">By</td>
                        <td class="s9">Marketing Cost</td>
                        <td class="s10"> {{ number_format($marketing_cost, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s7">By</td>
                        <td class="s9">Other General Cost</td>
                        <td class="s11"> {{ number_format($other_general_cost, 2) }} </td>
                        {{-- <td class="s11"> {{ number_format($other_general_cost, 2) }} </td> --}}
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s7"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        {{-- <td class="s10"> {{ number_format($rightSideSumTotal, 2) }} </td> --}}
                        <td class="s10"> {{ number_format((float) $right_side_round, 2) }} </td>

                    </tr>
                    <tr style="height: 20px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s7"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s7">By</td>
                        <td class="s8">Closing Balance</td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s7"></td>
                        <td class="s8">Cash at Bank</td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s7"></td>
                        <td class="s9">Indian Bank, Kolkata Ballygunge Br.</td>
                        <td class="s10"> {{ number_format($closingBankRight, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s7"></td>
                        <td class="s9">A/C No.CA:7815227267</td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s7"></td>
                        <td class="s8"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s7"></td>
                        <td class="s9">Cash In Hand</td>
                        <td class="s11"> {{ number_format($closingCashRight, 2) }} </td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s10"> {{ number_format($total_cloes_balance, 2) }} </td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s12"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s12"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s9"></td>
                        <td class="s13"> {{ number_format($lift_side_gran_total_balance, 2) }}</td>
                        <td class="s9"></td>
                        <td class="s10"> - </td>
                        <td class="s9"></td>
                        <td class="s13"> {{ number_format($right_side_gran_total_balance, 2) }} </td>



                    </tr>
                </tbody>
            </table>



            <br><br><br>
            {{-- <br><br><br><br><br><br><br><br><br><br><br> --}}
        </div>




        <div class="ritz grid-container auto-scale" dir="ltr">
            <table class="waffle" cellspacing="0" cellpadding="0">
                <thead>
                     <tr>
                        <!-- <th class="row-header freezebar-origin-ltr"></th> -->
                        <th id="1345355025C0" style="width:25px;" class="column-headers-background"></th>
                        <th id="1345355025C1" style="width:270px;" class="column-headers-background"></th>
                        <th id="1345355025C2" style="width:109px;" class="column-headers-background"></th>
                        <th id="1345355025C3" style="width:116px;" class="column-headers-background"></th>
                        <th id="1345355025C4" style="width:25px;" class="column-headers-background"></th>
                        <th id="1345355025C5" style="width:270px;" class="column-headers-background"></th>
                        <th id="1345355025C6" style="width:109px;" class="column-headers-background"></th>
                        <th id="1345355025C7" style="width:116px;" class="column-headers-background"></th>
                    </tr>
                </thead>
                <tbody>
                                       <tr style="height: 19px">

                        <td class="s0" colspan="8">{{ $appUser->app_u_name }}</td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s0" colspan="8">CIN No - {{ $appUser->cin_no }}</td>
                    </tr>

                    <tr style="height: 19px">

                        <td class="s1" colspan="8">{{ $appUser->app_u_address ?? 'NA' }}</td>
                        {{-- <td class="s1" colspan="8">Vill/Town:41/B Haripada Lane , Udayan Apartment Flat no-5</td> --}}
                    </tr>
                    <tr style="height: 19px">

                        <td class="s2" colspan="8">P.S: {{ $appUser->police_station ?? 'NA' }}, Dist:
                            {{ $appUser->user_district ?? 'NA' }}, Pin: {{ $appUser->pin_code ?? 'NA' }},
                            {{ $appUser->user_state ?? 'NA' }}</td>
                    </tr>
                    <tr style="height: 9px">

                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s2" colspan="8" style=" text-decoration: none; ">Mob No -
                            {{ $appUser->phone_number ?? 'NA' }} | Email Id - {{ $appUser->user_email ?? 'NA' }}</td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s4" colspan="8">
                            Income & Expenditure Account For The Year {{ $firstDate }} to {{ $lastDate }}
                        </td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s5"></td>
                        <td class="s5">Expenditure</td>
                        <td class="s5">Amount</td>
                        <td class="s5">Amount</td>
                        <td class="s5"></td>
                        <td class="s5">Income</td>
                        <td class="s5">Amount</td>
                        <td class="s5">Amount</td>
                    </tr>


                    
                    <tr style="height: 19px">

                        <td class="s6">To</td>
                        <td class="s3">Daily Collection Loan</td>
                        <td class="s7"> {{ number_format($daily_collection_loan, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6">By</td>
                        <td class="s3">Credit EMI</td>
                        <td class="s7"> {{ number_format($toCreditEMI, 2) }} </td>
                        <td class="s3"></td>
                    </tr>



                    <tr style="height: 19px">

                        <td class="s6">To</td>
                            <td class="s3">Weekly Collection Loan</td>
                        <td class="s7"> {{ number_format($weekly_collection_loan, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6">By</td>
                        <td class="s3">Short term borrowing</td>
                        <td class="s7"> {{ number_format($short_term_borrowing, 2) }} </td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s6">To</td>
                      <td class="s3">Biweekly Collection Loan</td>
                        <td class="s7"> {{ number_format($bi_weekly_collection_loan, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6">By</td>
                        <td class="s3">Long term borrowing</td>
                        <td class="s7"> {{ number_format($long_term_borrowing, 2) }} </td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s6">To</td>
                         <td class="s3">Monthly Loan</td>
                        <td class="s7"> {{ number_format($monthly_collection_loan, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6">By</td>
                        <td class="s3">Membership charge</td>
                        <td class="s7"> {{ number_format($membership_charge, 2) }} </td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s6">To</td>
                       <td class="s3">Fund Saving Withdraw</td>
                        <td class="s7"> {{ number_format($fund_saving_withdraw, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6">By</td>
                        <td class="s3">Processing charge</td>
                        <td class="s7"> {{ number_format($processing_charge, 2) }} </td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s6">To</td>
                              <td class="s3">Interest paid on Fund Saving Amount</td>
                        <td class="s7"> {{ number_format($total_rd_interest, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6">By</td>
                        <td class="s3">Insurance charge</td>
                        <td class="s7"> {{ number_format($insurance_charge, 2) }} </td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s6">To</td>
                     <td class="s3">Interest Paid on Loan Taken</td>
                        <td class="s7"> {{ number_format($interest_paid_on_loan, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6">By</td>
                        <td class="s3">Intarest Received on Microfinance Loan</td>
                        <td class="s7"> {{ number_format($IntarestReceivedOnMicrofinanceLoan, 2) }} </td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s6">To</td>
                                      <td class="s3">Other Charges Paid for Loan Taken</td>
                        <td class="s7"> {{ number_format($other_charges_paid_for_loan_taken, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6">By</td>
                        <td class="s3">Fund Saving Amount</td>
                        <td class="s7"> {{ number_format($total_rd_interest, 2) }} </td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s6">To</td>
                              <td class="s3">Insurance Charge</td>
                        <td class="s7"> {{ number_format($insurance_charge, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6">By</td>
                        <td class="s3">Penalty</td>
                        <td class="s7"> {{ number_format($penalty, 2) }} </td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s6">To</td>
                        <td class="s3">Director Salary</td>
                        <td class="s7"> {{ number_format($director_salary, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6">By</td>
                        <td class="s3">Others</td>
                        <td class="s11"> {{ number_format($others, 2) }} </td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s6">To</td>
                                        <td class="s3">Staff Salary</td>
                        <td class="s7"> {{ number_format($staff_salary, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s7"> {{ number_format($leftSideSum, 2) }} </td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s6">To</td>
                    <td class="s3">Staff Uniform & ID Card</td>
                        <td class="s7"> {{ number_format($staff_uniform_id_card, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s6">To</td>
                        <td class="s3">Staff Training</td>
                        <td class="s7"> {{ number_format($staff_training, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s6">To</td>
                            <td class="s3">Customer Awareness Camp</td>
                        <td class="s7"> {{ number_format($customer_awareness_camp, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s6">To</td>
                  <td class="s3">Cultural Programme</td>
                        <td class="s7"> {{ number_format($cultural_programme, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s6">To</td>
                        <td class="s3">Social Welfare Activity</td>
                        <td class="s7"> {{ number_format($social_welfare_activity, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s6">To</td>
                                 <td class="s3">Office Rent</td>
                        <td class="s7"> {{ number_format($office_rent, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s6">To</td>
                <td class="s3">Electricity Bill</td>
                        <td class="s7"> {{ number_format($electricity_bill, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s6">To</td>
                       <td class="s3">Mobile Recharge & Wi-Fi Bill</td>
                        <td class="s7"> {{ number_format($internet_mobile_recharge, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s6">To</td>
                     <td class="s3">Marketing Cost</td>
                        <td class="s7"> {{ number_format($marketing_cost, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s6">To</td>
                      <td class="s3">Other General Cost</td>
                        <td class="s11"> {{ number_format($other_general_cost, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s6"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">

                        {{-- <td class="s6">To</td>
                        <td class="s3">Other General Cost</td>
                        <td class="s11"> {{ number_format($other_general_cost, 2) }} </td> --}}
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s7"> {{ number_format($rightSideSum + $other_general_cost, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s6">To</td>
                        <td class="s3">Net Surplus</td>
                        <td class="s3"></td>
                        <td class="s7"> {{ number_format($ToNetSurplus, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s3"></td>
                        <td class="s3">(Excess Income Over Expenditure)</td>
                        <td class="s3"></td>
                        <td class="s12"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s12"></td>
                    </tr>
                    <tr style="height: 20px">

                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s13"> {{ number_format($ExcessIncomeOver, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s7"> - </td>
                        <td class="s3"></td>
                        <td class="s13"> {{ number_format($leftSideSum, 2) }} </td>
                    </tr>
                </tbody>
            </table>


            <br><br><br>
            {{-- <br><br><br><br><br><br><br><br><br><br><br> --}}

        </div>


        <div class="ritz grid-container auto-scale" dir="ltr">
            <table class="waffle" cellspacing="0" cellpadding="0">
                <thead>
                     <tr>
                        <!-- <th class="row-header freezebar-origin-ltr"></th> -->
                        <th id="1345355025C0" style="width:25px;" class="column-headers-background"></th>
                        <th id="1345355025C1" style="width:270px;" class="column-headers-background"></th>
                        <th id="1345355025C2" style="width:109px;" class="column-headers-background"></th>
                        <th id="1345355025C3" style="width:116px;" class="column-headers-background"></th>
                        <th id="1345355025C4" style="width:25px;" class="column-headers-background"></th>
                        <th id="1345355025C5" style="width:270px;" class="column-headers-background"></th>
                        <th id="1345355025C6" style="width:109px;" class="column-headers-background"></th>
                        <th id="1345355025C7" style="width:116px;" class="column-headers-background"></th>
                    </tr>
                </thead>
                <tbody>
                                       <tr style="height: 19px">

                        <td class="s0" colspan="8">{{ $appUser->app_u_name }}</td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s0" colspan="8">CIN No - {{ $appUser->cin_no }}</td>
                    </tr>

                    <tr style="height: 19px">

                        <td class="s1" colspan="8">{{ $appUser->app_u_address ?? 'NA' }}</td>
                        {{-- <td class="s1" colspan="8">Vill/Town:41/B Haripada Lane , Udayan Apartment Flat no-5</td> --}}
                    </tr>
                    <tr style="height: 19px">

                        <td class="s2" colspan="8">P.S: {{ $appUser->police_station ?? 'NA' }}, Dist:
                            {{ $appUser->user_district ?? 'NA' }}, Pin: {{ $appUser->pin_code ?? 'NA' }},
                            {{ $appUser->user_state ?? 'NA' }}</td>
                    </tr>
                    <tr style="height: 9px">

                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                        <td class="s2"></td>
                    </tr>
                    <tr style="height: 19px">

                        <td class="s2" colspan="8" style=" text-decoration: none; ">Mob No -
                            {{ $appUser->phone_number ?? 'NA' }} | Email Id - {{ $appUser->user_email ?? 'NA' }}</td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s4" colspan="8">Balance Sheet As On {{ $firstDate }} to {{ $lastDate }}</td>

                    </tr>
                    <tr style="height: 19px">
                        <td class="s5"></td>
                        <td class="s5">Liabilities</td>
                        <td class="s5">Amount</td>
                        <td class="s5">Amount</td>
                        <td class="s5"></td>
                        <td class="s5">Assets</td>
                        <td class="s5">Amount</td>
                        <td class="s5">Amount</td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s8">General Fund</td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s8">Fixed Assets</td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s3">As Per Last Account</td>
                        <td class="s3"></td>
                        <td class="s7"> {{ number_format($LastAccount, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s3">Furniture</td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s3">Add: Net Surplus</td>
                        <td class="s3"></td>
                        <td class="s9"> {{ number_format($ToNetSurplus, 2) }} </td>
                        <td class="s3"></td>
                        <td class="s3">As Per Last A/c</td>
                        <td class="s3"></td>
                        <td class="s3">{{ number_format($FixedAssetsFurniture, 2) }}</td>
                        <td class="s9"></td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s3">(Excess Income Over Expenditure)</td>
                        <td class="s3"></td>
                        <td class="s10"></td>
                        <td class="s3"></td>
                        <td class="s3" dir="ltr">Computer & Printer</td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s7"></td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s11"></td>
                        <td class="s9"> {{ number_format($GeneralFundSum, 2) }} </td>
                        <td class="s3">As Per Last A/c</td>
                        <td class="s3"></td>
                        <td class="s3">{{ number_format($FixedAssetsComputer, 2) }}</td>
                        <td class="s9"></td>
                    </tr>
                    @if ($ToNetSurplus2 > 50000)
                        <tr style="height: 19px">
                            <td class="s3"></td>
                            <td class="s3"></td>
                            <td class="s3"></td>
                            <td class="s3"></td>
                            <td class="s3" dir="ltr">Laptop & Mobile</td>
                            <td class="s3"></td>
                            <td class="s3"></td>
                            <td class="s3"></td>
                        </tr>
                        <tr style="height: 19px">
                            <td class="s3"></td>
                            <td class="s3"></td>
                            <td class="s3"></td>
                            <td class="s3"></td>
                            <td class="s3">As Per Last A/c</td>
                            <td class="s3"></td>
                            <td class="s3">{{ number_format($FixedAssetsAC, 2) }}</td>
                            <td></td>
                        </tr>
                    @endif
                    <tr style="height: 19px">
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3 " dir="ltr">Office Equipment</td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s9" dir="ltr"></td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3">As Per Last A/c</td>
                        <td class="s3"></td>
                        <td class="s11">{{ number_format($FixedAssetEquipment, 2) }}</td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s9" dir="ltr"> {{ number_format($FixedAssetsSum, 2) }} </td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s8">Outstanding Liabilities</td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s3">Add:Sundry Creditors</td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s9" dir="ltr"> {{ number_format($SundryCreditors, 2) }} </td>
                        <td class="s8">Cash at Bank</td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3">Indian Bank, Kolkata Ballygunge Br.</td>
                        <td class="s3"></td>
                        <td class="s9"> {{ number_format($CashAtBankLeft, 2) }} </td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3">A/C No.CA:7815227267</td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s6"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 19px">
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3">Cash In Hand</td>
                        <td class="s3"></td>
                        <td class="s11"> {{ number_format($CashAtHandLeft, 2) }} </td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s9"> {{ number_format($BalanceSheetinBank, 2) }} </td>
                    </tr>
                    <tr style="height: 20px">
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                    </tr>
                    <tr style="height: 20px">
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s12"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s12"></td>
                    </tr>
                    <tr style="height: 20px">
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s3"></td>
                        <td class="s13"> {{ number_format($BalanceSheetLeftSum, 2) }} </td>
                        <td class="s3"></td>
                        <td></td>
                        <td class="s3"></td>
                        <td class="s13"> {{ number_format($BalanceSheetRightSum, 2) }} </td>
                    </tr>
                </tbody>
            </table>


            <br><br><br>
            {{-- <br><br><br><br><br><br><br><br><br><br><br> --}}

        </div>




    </div>
</body>

</html>
