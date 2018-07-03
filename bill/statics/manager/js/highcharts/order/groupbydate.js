;(function(){
    this.ajaxTransSubmit = function ( formId ){
        $form = $( '#'+formId );
        if ( $form.data('isBind') ) return;
        $form.data('isBind', 1);
        $form.submit(function() {
            $.get($form.action, function ( data ){
                var obj = data;
                if( obj=='undefined' ){
                    alert( data );
                    return false;
                }else{
                    var options = {
                        chart: {
                            renderTo: 'charts1',
                            type: 'column',
                            width:740
                        },
                        title: {
                            text: '订单时段统计'
                        },
                        subtitle: {
                            text: ''
                        },
                        credits: {
                            enabled: false
                        },
                        xAxis: {
                            title: {
                                text: '日期',
                                align: 'low',
                                offset: 15
                            },
                            categories: [

                            ]
                        },
                        yAxis: {
                            title: {
                                text: '数量',
                                align: 'high',
                                rotation: 0,
                                offset: -3,
                                y: -10
                            }
                        },
                        legend: {
                            enabled: false
                        },
                        tooltip: {
                            formatter: function() {
                                var point = this.point,
                                s = this.x +'：<b>'+ this.y +' </b><br/>';
                                return s;
                            }
                        },
                        series: [ {
                            data: [

                            ],
                            dataLabels: {
                                enabled: true,
                                rotation: 0,
                                align: 'center',
                                x: 0,
                                y: -5,
                                formatter: function() {
                                    return this.y;
                                },
                                style: {
                                    font: 'normal 13px Verdana, sans-serif'
                                }
                            }
                        } ]
                    };

                    var objLen = obj.length;

                    //if( formId=='frm_top' ){

                        for( var index=0;index<objLen;index++ ){
                            //options.xAxis.categories.push( obj[index].active_date.replace('-','/') );
                            options.series[0].data.push( parseInt( obj[index].entity_total_sum ))
                        }

                        if( objLen<10 ){
                            options.chart.width = 740;
                        }else{
                            options.chart.width = (2300-740)/(objLen-10)*32;
                        }

                        if( objLen<=7 ){
                            options.subtitle.text = '近7天安装总量分布图';

                        }else if( objLen>7 ){
                            if( $( '#hdn_top_rado' ).attr( 'checked' ) ){
                                var dateInfo = obj[0].active_date.split( '-' );
                                options.subtitle.text = dateInfo[0]+'年'+dateInfo[1]+'月安装总量分布图';
                            }else{
                                options.subtitle.text = '近30天安装总量分布图';
                            }
                        }

                        window.charts = new Highcharts.Chart(options);
                    //}
                }
            });
            return false;
        });
    }


    this.rendering = function (formId){
        ajaxTransSubmit(formId);
        $('#'+formId).submit();
    }

    this.validateYearAndMonth = function ( site ){

        var year = parseInt( $( '#slct_'+site+'_year' ).val() );
        var month = parseInt( $( '#slct_'+site+'_month' ).val() );

        if( year=='' ){
            alert( '请选择年' );
            return false;
        }
        if( month=='' ){
            alert( '请选择月' );
            return false;
        }

        var myDate = new Date();
        nowYear = myDate.getFullYear();
        nowMonth = myDate.getMonth()+1;
        nowMonth = nowMonth>10?nowMonth:'0'+nowMonth;

        if( year==nowYear&&month>nowMonth ){
            alert( '日期选择有误，请重新选择' );
            return false;
        }

        $( '#hdn_'+site+'_date' ).val( year+'-'+month );
        return true;
    }

})(this);
