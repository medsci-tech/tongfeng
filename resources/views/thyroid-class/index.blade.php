@extends('layouts.open')

@section('title','公开课首页')

@section('page_id','open_course')

@section('css')
  <link rel="stylesheet" href="/vendor/swiper/swiper-3.3.0.min.css">
  <link rel="stylesheet" href="/css/thyroid-class.css">
  <link rel="stylesheet" href="/css/model.css">
@endsection

@section('content')

  @include('layouts.header')
  <div class="modal fade" tabindex="-1" role="dialog" id="activity_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <div class="modal-body">
          <img src="{{ asset('/img/close.jpg') }}" style="width: 40px;position: relative;float: right;cursor: pointer;" id="activity_close">
          <img src="{{ asset('/img/20180301210607-tf.png') }}" style="max-width: 500px;">
          {{--<img src="{{ asset('airclass/img/unlock.png') }}" style="width: 36%;margin-top:-21%;margin-left: 30%;position: relative;display:block;cursor: pointer;" id="activity_img">--}}
          <a class="btn-primary" href="http://wechat.mime.org.cn/register?from=1">立即登入</a>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="swiper-container">
      <div class="swiper-wrapper">
        <div class="swiper-slide" v-for="slide in swiper_pictures">
          <a href="@{{slide.href}}">
            <img :src="slide.image" alt="@{{slide.name}}">
          </a>
        </div>
      </div>
    </div>
  </div>


  <br>
  <div class="row information">
    <div class="header hide-for-small-only">
      <div></div>
      <span v-for="header in main_class.header">@{{ header }}</span>
    </div>

    <div>
      <div class="medium-6 small-12 columns">
        <div id="id_video_container" style="width:100%;"></div>
      </div>
      <div class="medium-6 small-12 columns">
        <h4>@{{ main_class.body.title }}</h4>

        <p>@{{ main_class.body.paragraph }}<br><br></p>

        <div class="row align-top">
          <div class="medium-6 small-12 columns" v-for=" footer in main_class.footer">
            <p v-if="footer.title!==''"><i class="fa fa-@{{ footer.fa }}"></i>&nbsp;@{{ footer.title }}：@{{ footer.content }}</p>
          </div>
        </div>


        <div class="row">
          <div class="medium-6 small-12 columns">
            @if(\Session::has('studentId'))
              @if(\App\Models\Student::find(\Session::get('studentId'))->entered_at)
                <button type="button" class="expanded button" disabled>
                  已报名
                </button>
              @elseif(\Session::has('replenished') && \Session::get('replenished'))
                <button @click="register_course" type="button" class="expanded button">
                课程报名
                </button>
              @else
                <a type="button" class="expanded button" href="/home/replenish/create">
                  课程报名
                </a>
              @endif
            @else
              <a type="button" class="expanded button" href="/home/register/create">
                课程报名
              </a>
            @endif
          </div>
          <div class="medium-6 small-12 columns">
            <p>@{{ main_class.other_information }}</p>
          </div>
        </div>

      </div>
    </div>
  </div>
  <br>
  <div class="row collapse">
    <ul class="tabs" data-tabs id="example-tabs">
      <li class="tabs-title" v-for="tab in tabs"><a href="@{{'#panel'+$index }}">@{{ tab.name }}</a></li>
    </ul>
    <div class="tabs-content" data-tabs-content="example-tabs">
      <div class="tabs-panel is-active" id="panel0">
        <div class="row column" v-for="row in tabs[0].content">
          <div class="medium-4 small-12 columns">
            <div class="small-12">
              <img :src="row.teacher.image" alt="">
            </div>
            <div class="small-12">
              <p></p>
              {{-- <p>讲师：@{{ row.teacher.teacher_name }}</p> --}}

              <p>课程简介：@{{{ row.teacher.brief }}}</p>
            </div>
          </div>
          <div class="medium-8 small-12 columns align-top">
            <div class="medium-4 small-6 columns" style="height:255px;"
                 v-for="course in row.courses">
              <div class="small-12">
                <a href="@{{ course.href }}">
                  <img :src="course.image" alt="">
                </a>
              </div>
              <div class="small-12">
                <div>@{{ course.title }}</div>
                <div class="span" v-for="span in course.information"><i
                    class="fa fa-@{{ span.fa }}"></i>&nbsp;<span>@{{ span.title }}
                    <template v-if="span.title != ''&&span.title != null">：
                    </template>@{{ span.content }}</span>&emsp;
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tabs-panel" id="panel1">
        <div class="row" v-for="teacher in tabs[1].content">
          <div class="medium-3 small-12 columns">
            <img :src="teacher.headimg" alt="">
          </div>
          <div class="medium-9 small-12 columns">
          </div>
          <div>
            <strong>@{{ teacher.name }}&emsp;@{{ teacher.title }}</strong>&emsp;<span
              class="gray">@{{ teacher.office }}</span>
          </div>
          <br>

          <div>
            <div class="gray">导师简介：</div>
            <p>@{{{ teacher.bref }}}</p>
          </div>
        </div>
      </div>
      <div class="tabs-panel" id="panel2">
        <div class="row">

          <div>
            <div class="question-title"><strong>亚太痛风联盟（APGC）—高尿酸血症/痛风公开课是什么样的课程？</strong></div>
            <div class="question-text">
              <p>
                是由亚太痛风联盟（APGC）发起的全国首个高尿酸血症与痛风领域的大型公开课，旨在为广大医生提供系统性和实用性的临床诊疗技能，推动国内各学科对高尿酸血症及其相关疾病的认识，规范和指导其临床实践，造福全球高尿酸血症与痛风患者。  
              </p>
            </div>
          </div>
          <div>
            <div class="question-title"><strong>痛风公开课收费吗？</strong></div>
            <div class="question-text">
              <p>完全免费，欢迎医学爱好者报名参加，并分享给您身边的人。</p>
            </div>
          </div>
          <div>
            <div class="question-title"><strong>痛风公开课的授课内容？</strong></div>
            <div class="question-text">
              <p>课程共分为7个系列，每个系列围绕各自的侧重点详细讲述：</p>
              <br>

              <p style="margin-left:30px;">（1）高尿酸血症与痛风的前世今生：历史与流行病学、危害证据、病因机制与预防；</p>

              <p style="margin-left:30px;">（2）高尿酸血症和痛风的诊断之旅：除诊断标准外，重点围绕影像学检查展开；</p>

              <p style="margin-left:30px;">（3）防治武器：药物、饮食、运动、患者管理；</p>

              <p style="margin-left:30px;">（4）治疗之旅：标化治疗方案以及每一个临床分期的治疗方案及策略；</p>

              <p style="margin-left:30px;">（5）相关合并症(肾脏、心血管、糖尿病、代谢综合征)的研究进展与诊治；</p>

              <p style="margin-left:30px;">（6）特殊类型痛风的临床特点及诊疗策略：儿童、女性、老年、难治性痛风等；</p>
              <p style="margin-left:30px;">（7）国内外共识及指南的解读。</p>
            </div>
          </div>
          <div>
            <div class="question-title"><strong>有哪些授课专家?</strong></div>
            <div class="question-text">
              <p>李长贵教授、孙明姝教授、孙瑞霞教授、陈海冰教授、姜林娣教授、宋惠教授、余学锋教授</p>
            </div>
          </div>
          <div>
            <div class="question-title"><strong>课程报名时间与上课时间？</strong></div>
            <div class="question-text">
              <p>报名时间为8月10日，暂未制定报名截止时间，随时报名随时学习；</p>

              <p>正式开课时间为9月7日，每周四下午18：00更新课程。</p>
            </div>
          </div>
          <div>
            <div class="question-title"><strong>痛风公开课的学习方式？</strong></div>
            <div class="question-text">
              <p>1. 关注微信号：迈德医学V，点击子菜单中这里有课—痛风公开课学习</p>

              <p>2. 登陆专题网站：open.mime.org.cn</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @if(\Session::has('studentId') && $aciviveNaire!=null && \App\Models\QuestionResult::where('n_id',$aciviveNaire->id)->where('s_id',\Session::get('studentId'))->first()==null)
    <div id="ebooking_fs" style="position: fixed; top: 80px; right: 10px;z-index:99999;">
        <a style="font-size: 22px;
    font-weight: 600;
    color: blue;" href="/home/question/{{$aciviveNaire->id}}" target="_blank"><img style="width: 180px;" src="/image/questionnaire.png"></a>
    </div>
    <script>
      //window.open("/home/question/{{$aciviveNaire->id}}","_blank");
    </script>
  @endif
  @include('layouts.footer')
  
@endsection


@section('js')
  <script>
    @if(\Session::has('studentId') && $aciviveNaire!=null && \App\Models\QuestionResult::where('n_id',$aciviveNaire->id)->where('s_id',\Session::get('studentId'))->first()==null)
          window.location.href = '/home/question/{{$aciviveNaire->id}}';
    @endif
  </script>
  {{--    @include('home.login-modal')--}}
  <script src="/vendor/swiper/swiper-3.3.0.min.js"></script>
  <script>
    vm = new Vue({
      el: '#open_course',
      data: {
        top_bar_left: [
          {
            name: '首页',
            href: '/'
          }, {
            name: '课程',
            href: '#'
          }, {
            name: '讲座',
            href: '#'
          }, {
            name: '病例讲座',
            href: '#'
          }, {
            name: '直播',
            href: '#'
          }
        ],

        top_bar_right: [
            @if(\Session::has('studentId'))
            @if(\Session::has('replenished') && \Session::get('replenished'))
          {
            name: '{{\App\Models\Student::find(Session::get("studentId"))->name}}',
            href: '#'
          },
            @else
          {
            name: '{{\App\Models\Student::find(Session::get("studentId"))->phone}}',
            href: '#'
          },
            @endif
          {
            name: '退出',
            href: '/home/logout'
          },

            @else
          {
            name: '登录',
            href: '/home/login'
          },
          {
            name: '注册',
            href: '/home/register/create'
          }
          @endif
        ],

        swiper_pictures: [
          @foreach($banners as $banner)
          {
            name: '',
            image: '{{$banner->image_url}}',
            href: '{{$banner->href_url}}'
          },
          @endforeach
        ],

        main_class: {
          header: [
              '高尿酸血症/痛风公开课',
              {{--'更新至{{$thyroidClass->latest_update_at}}期', --}}
              {{--'播放总次数{{$playCount}}'--}}
          ],

          body: {
            title: '课程介绍',
            image: '/image/test.jpg',
            paragraph: '{{$thyroidClass->comment}}'
          },

          footer: [{
            title: '更新时间',
            fa: 'clock-o',
            content: '每周四 18:00'
          }, {
            title: '开课时间',
            fa: 'video-camera',
            content: '2017年9月'
          }, {
            title: '学习时间',
            fa: 'calendar',
            content: '2017年9月-12月'
          }, {
            title: '',
            fa: '',
            content: ''
          }],

          other_information: '已有{{$studentCount}}人注册了痛风公开课'
        },

        tabs: [
          {
            name: '课程内容',
            content: [
                @foreach($thyroidClassPhases as $thyroidClassPhase)
              {
                teacher: {
                  title: '{{$thyroidClassPhase->title}}',
                  image: '{{$thyroidClassPhase->logo_url}}',
                  teacher_name: '{{$thyroidClassPhase->teacher ?$thyroidClassPhase->teacher->name :''}}',
                  brief: '{!!  $thyroidClassPhase->comment !!}'
                },
                courses: [
                    @foreach($thyroidClassPhase->thyroidClassCourses as $thyroidClassCourse)
                  {
                    title: '{{$thyroidClassCourse->title}}',
                    image: '{{$thyroidClassCourse->logo_url}}',
                    href: '/thyroid-class/course/view?course_id={{$thyroidClassCourse->id}}',
                    information: [
                      {
                        title: '学习',
                        fa: 'youtube-play',
                        content: '{{$thyroidClassCourse->play_count}}人'
                      }
                    ]
                  },
                  @endforeach
                ]
              },
              @endforeach
            ]
          },
          {
            name: '授课老师',
            content: [
                @foreach($teachers as $teacher)
              {
                headimg: '{{$teacher->photo_url}}',
                name: '{{$teacher->name}}',
                title: '{{$teacher->title}}',
                office: '{{$teacher->office}}',
                bref: '{!! $teacher->introduction !!}'
              },
              @endforeach
            ]
          },
          {
            name: '常见问题',
            content: []
          }
        ]
      },
      methods: {
        register_course: function () {
          $.post('/thyroid-class/enter', '', function (data) {
            history.go(0);
          })
        }
      }
    });

    var swiper = new Swiper('.swiper-container', {
      autoHeight: true,
      loop: true,
      autoplay: {{$thyroidClass->banner_autopaly}},
      autoplayDisableOnInteraction : false,
      speed: 800
    });

    $(function () {
      $('.tabs-title').eq(0).addClass('is-active');
      $('.tabs-title').eq(0).children('a').attr('aria-selected', "true");
      $('#panel0>div>div').find('.medium-4:last').addClass('end');
//            $("a[href='/home/login'],button[href='/home/login']").attr({
//                'href': '#',
//                'data-open': 'exampleModal1'
//            });
    })

  </script>
  <script src="http://qzonestyle.gtimg.cn/open/qcloud/video/h5/h5connect.js"></script>
  <script type="text/javascript">
    (function () {
        $('#activity_close').click(function(){
            $('.modal').fadeOut();
        })
        /*var key = {{ Redis::get('user-tf:'.session('studentId').':mime')?:0}};

        if(key===1 ){
            $('.modal').fadeIn();
            $.get("{{url('/home/incrTimes')}}",function(){});
        }*/
      var option = {
        "auto_play": "0",
        "file_id": "{{$thyroidClass->qcloud_file_id}}",
        "app_id": "{{$thyroidClass->qcloud_app_id}}",
        "width": 1920,
        "stretch_patch": true,
        "height": 1080
      };
      /*调用播放器进行播放*/
      new qcVideo.Player(
        /*代码中的id_video_container将会作为播放器放置的容器使用,可自行替换*/
        "id_video_container",
        option
      );
    })()
  </script>
  <script>
    $(document).foundation();
    var obj = $("#ebooking_fs");
    if (obj) {
      /*
      var _box_y = obj.offset().top; 
      var _box_x = obj.offset().left; 
      $(window).scroll(function(){ 
        if($(window).scrollTop() > _box_y){ 
         //  $("#ebooking_fs").attr("style","top:"+($(window).scrollTop()+_box_y)+"px; z-index:99;"); 
          // obj.css('top',($(window).scrollTop()+_box_y) +"px");
        }else{ 
        //  $("#ebooking_fs").attr("style",""); 
        } 
      })
      */
    } 
  </script>
@endsection
