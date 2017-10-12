@extends('layouts.open')

@section('title','痛风公开课报名')

@section('page_id','sign_up')

@section('css')
  <style>
    .log-in-form {
      /*border: 1px solid #cacaca;*/
      padding: 1rem 1rem !important;
      border-radius: 3px;
    }

    .help-text {
      color: #ec5840;
    }

    textarea:disabled {
      height: 10rem;
      overflow-y: auto;
      cursor: inherit;
      background-color: #fefefe;
      font-size: 90%;
    }
    .region-group{
      width: 100%;
      padding-left: -8px;
      margin: 0 0 1rem;
    }
    .region-select{
      width: 30%;
      display: inline-block;
      float: left;
      margin: 0 1.6%;

    }

    /*清除浮动*/
    .clearfix:before,.clearfix:after{
      content:"";
      display:table;
    }
     .clearfix:after{
       clear:both;
     }
      .clearfix{
        *zoom:1;/*IE/7/6*/
      }

       
  </style>
@endsection

@section('content')
  <div class="row">
    <div class="medium-6 medium-centered large-4 large-centered columns">
      <br>

      <form method="post" action="/home/register/store">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <input type="hidden" name="promo_code" value="{{ $promo_code }}"/>

        <div class="row column log-in-form">
          <h4 class="text-center">痛风公开课报名</h4>
          <label>手机号
            <input required v-model="phone" type="text" value="{{ old('phone') }}" placeholder="请输入您的手机号" name="phone">
          </label>
          <p id="error_phone" class="help-text hide">请输入正确的手机号!</p>
          @if($errors->has('phone'))
            <p class="help-text">{{ $errors->first('phone')}}</p>
          @endif
          <label>验证码
            <div class="input-group">
              <input required v-model="sms" class="input-group-field" type="text" placeholder="请输入验证码"
                     name="auth_code">

              <div class="input-group-button">
                <button @click="get_auth_code" type="button" class="button">获取验证码</button>
              </div>
            </div>
          </label>

          @if($errors->has('auth_code'))
            <p class="help-text">{{ $errors->first('auth_code')}}</p>
          @endif
          <label>密码
            <input required v-model="password" type="password" placeholder="6-16位，区分大小写，不可用特殊符号" name="password">
          </label>
          @if($errors->has('password'))
            <p class="help-text">{{ $errors->first('password')}}</p>
          @endif
          <label>确认密码
            <input required v-model="password_confirmation" type="password" placeholder="请再次输入密码" name="password_confirmation">
          </label>
          @if($errors->has('password_confirmation'))
            <p class="help-text">{{ $errors->first('password_confirmation')}}</p>
          @endif
          <p v-show="is_same" class="help-text">两次输入的密码不一致!</p>


          <label>姓名
              <input required type="text" value="{{ old('name') }}" placeholder="请输入" name="name">
          </label>

          @if($errors->has('name'))
              <p class="help-text">{{ $errors->first('name')}}</p>
          @endif

          <div style="font-size: .875rem">
              <div class="small-2 columns" style="padding-left: 0;">性别</div>
              <label class="small-5 columns">
                  <input  type="radio" value="1" name="sex">男
              </label>
              <label class="small-5 columns">
                  <input  type="radio" value="0" name="sex">女
              </label>
          </div>
          @if($errors->has('sex'))
              <p class="help-text">{{ $errors->first('sex')}}</p>
          @endif

          <label>出生日期
              <input required type="date" value="{{ old('birthday') }}" placeholder="选择出生日期" name="birthday">
          </label>
          @if($errors->has('birthday'))
              <p class="help-text">{{ $errors->first('birthday')}}</p>
          @endif

          <label>邮箱
              <input required type="email" placeholder="请输入" name="email"
                      value="{{old('email')}}">
          </label>

          @if($errors->has('email'))
              <p class="help-text">{{ $errors->first('email')}}</p>
          @endif


          
            <div class="clearfix region-group" id="city-select">

            <label>地区</label>
              <select class="region-select" name="province">
                <option value="" selected="selected">-选择省-</option>
              </select>
              
              <select class="region-select" name="city">
                <option value="" selected="selected">-选择市-</option>
              </select>
              <select class="region-select" name="area">
                <option value="" selected="selected">-选择区-</option>
              </select>
              <!--省市区-->
              <input id="save-province" type="hidden"  name="save-province">
              <input id="save-city" type="hidden" name="save-city">
              <input id="save-area" type="hidden" name="save-area">
            </div>
          
          @if($errors->has('save-area'))
            <p class="help-text">{{ $errors->first('save-area')}}</p>
          @endif

          <label style="clear:both">医院
            <input required type="text" placeholder="请输入医院" name="hospital" value="{{old('hospital')}}">
          </label>
          @if($errors->has('hospital'))
            <p class="help-text">{{ $errors->first('hospital')}}</p>
          @endif
          <label>医院级别
            <select required name="hospital_level" @change='chooseOtherHosLevel(this)'>
              <option value="" >-请选择医院级别-</option>
              @foreach(config('params')['hospital_level'] as $value)
              <option value="{{$value}}">{{$value}}</option>
              @endforeach
            </select>
          </label>
          <input required type="text" style="display: none;" placeholder="请输入医院级别" name="save_hospital_level" >
          @if($errors->has('hospital_level'))
            <p class="help-text">{{ $errors->first('hospital_level')}}</p>
          @endif
          <label>科室
            <select required name="office" @change='chooseOtherOffice(this)'>
              <option value="" >-请选择科室-</option>
              @foreach(config('params')['doctor_office'] as $value)
              <option value="{{$value}}">{{$value}}</option>
              @endforeach
            </select>
          </label>
          <input required type="text" style="display: none;" placeholder="请输入科室" name="save_office" >
          @if($errors->has('office'))
            <p class="help-text">{{ $errors->first('office')}}</p>
          @endif
          <label>职称
            <select required name="title" @change='chooseOtherTitle(this)'>
              <option value="" >-请选择职称-</option>
              @foreach(config('params')['doctor_title'] as $value)
                <option value="{{$value}}">{{$value}}</option>
              @endforeach
            </select>
          </label>
          <input required type="text" style="display: none;" placeholder="请输入职称" name="save_title" >
          @if($errors->has('title'))
            <p class="help-text">{{ $errors->first('title')}}</p>
          @endif



          <div style="font-size: .875rem">用户协议
            <textarea disabled>
第1条关于本协议

1.1 迈德同信（武汉）科技股份有限公司及其关联公司（以下统称为“迈德同信”）旗下拥有包括医师助手APP、MIME网站、医师助手DocMate微信号、易康商城、QQ群等在内的移动医学教育资源（统称“教育平台”），并实行同一账号在教育平台中通行使用，即为“迈德通行证”。您在教育平台中任一组成部分注册成为用户后即可获得“迈德通行证”。
1.2 本协议是您与迈德同信之间就教育平台服务等相关事宜所订立的契约，请您仔细阅读本协议，您点击“注册”按钮后，本协议即构成对双方有约束力的法律文件。
1.3 根据国家法律法规变化及网络运营需要，迈德同信有权根据需要对本协议条款进行修改，修改后的协议自发布在教育平台上即生效，并代替原来的协议。用户有权且有义务及时阅读最新版的协议及教育平台公告。如用户不同意更新后的协议，可以且应立即停止接受教育平台依据本协议提供的服务；如用户继续使用教育平台提供的服务，即视为同意现行有效的服务协议内容。迈德同信建议您在使用教育平台之前阅读本协议及教育平台公告。如果本协议中任何一条被视为废止、无效或因任何理由不可执行，该条应视为可分的且并不影响任何其余条款的有效性和可执行性。

第2条关于教育平台

2.1 迈德同信通过互联网依法为用户提供互联网信息等服务，包括但不限于：内分泌或其他领域常见病症的视频课件教学，医学知识和经验的学习交流，科普知识普及，相关产品及药品的使用知识，案例推送，知识产权代理等功能或内容。用户在完全同意本协议及教育平台规定的情况下方有权使用教育平台的相关服务。
2.2 用户必须自行准备如下设备和承担如下开支：
（1）上网设备，包括并不限于电脑或者其他上网终端、调制解调器及其他必备的上网装置；
（2）上网开支，包括并不限于网络接入费、上网设备租用费、手机流量费等。

第3条关于本服务

3.1 商品信息
教育平台上的服务价格、数量、能否提供等信息随时都有可能发生变动，教育平台不作特别通知。教育平台显示的信息可能会有一定的滞后性或差错，对此情形您知悉并理解；迈德同信欢迎纠错，并会视情况给予纠错者一定的奖励。为表述便利，产品和服务简称为“产品”或“服务”。
3.2 订单
3.2.1 在您下订单时，请您仔细确认所购服务的名称、价格、数量、说明、注意事项、联系地址、电话、联系人等信息。联系人与用户本人不一致的，联系人的行为和意思表示视为用户的行为和意思表示，用户应对联系人的行为及意思表示的法律后果承担连带责任。
3.2.2 除法律另有强制性规定外，双方约定如下：教育平台上展示的服务和价格等信息仅仅是要约邀请，您下单时须填写您希望购买的服务数量、价款及支付方式、联系人、联系方式、联系地址（合同履行地点）、合同履行方式等内容；系统生成的订单信息是计算机信息系统根据您填写的内容自动生成的数据，仅是您向迈德同信发出的合同要约；迈德同信收到您的订单信息后，只有在迈德同信将您在订单中订购的服务向您或指定联系人交付时（接受服务为准），方视为您与迈德同信之间就向您或指定联系人提供的服务建立了合同关系；如果您在一份订单里订购了多份服务并且迈德同信只给您或指定联系人交付了部分服务时，您与迈德同信之间仅就实际向您或指定联系人提供的服务建立了合同关系；只有在迈德同信实际向您或指定联系人提供了订单中订购的其他服务时，您和迈德同信之间就订单中该其他已实际向您或指定联系人提供的服务才成立合同关系。您可以随时登陆您在教育平台注册的账户，查询您的订单状态。
3.2.3 由于市场变化及各种以合理商业努力仍难以控制的因素影响，教育平台无法保证您提交的订单信息中希望购买的服务都能提供；如您拟购买的服务发生无法提供的情形，您有权取消订单。
3.2.4 教育平台仅为用户提供中文咨询及书面服务，若用户有英文服务需求，请致电教育平台服务部另行协商确定服务价格。
3.3 配送
3.3.1 迈德同信将会把产品（服务）发送到您或指定联系人所指定的接收端或邮箱等，所有在教育平台列出的交付时间为参考时间，参考时间的计算是根据具体服务的处理过程和发送时间的基础上估计得出的，并不作为实际交付时间的承诺。
3.3.2 因如下情况造成订单延迟或无法交付等，迈德同信不承担因延迟交付造成的相应责任：
（1）用户提供的信息错误、接受端设备等原因导致的；
（2）服务发送后无人查阅的；
（3）情势变更因素导致的；
（4）不可抗力因素导致的，例如：自然灾害、基础网络问题、突发战争、网络黑客等。
3.4 服务内容
3.4.1 服务条款
迈德同信需保证提供安全、稳定的服务场所，保证服务的顺利进行。
用户必须在注册及申请收费服务时，详细阅读教育平台使用说明信息，并严格按要求操作。在个人信息部分必须提供真实的用户信息，一旦发现用户提供的个人信息中有虚假，迈德同信有权立即终止向该用户提供的所有服务，冻结该用户的帐户，并有权要求用户赔偿因提供虚假信息给迈德同信造成的全部损失。
3.4.2 服务中迈德同信与用户双方的权利及义务
迈德同信有义务在现有技术上维护平台服务的正常进行，并努力提升技术及改进技术，使网站服务更好进行。
迈德同信保证视频教学服务由来自临床医学、基础医学、卫生统计学以及医学信息等领域的专业人员，学习交流平台以病例为核心，以临床数据库为支撑。
对于用户在教育平台预定服务中的不当行为或其它任何迈德同信认为应当终止服务的情况，迈德同信有权随时作出删除相关信息、终止服务提供等处理，而无须征得用户的同意。
如存在下列情况：
（1）用户或其它第三方通知迈德同信，认为某个具体用户或具体交易事项可能存在重大问题；
（2）用户或其它第三方向迈德同信告知咨询内容有违法或不当行为的，迈德同信以普通非专业的知识水平标准对相关内容进行判别，可以明显认为这些内容或行为具有违法或不当性质的。
迈德同信有义务对相关数据、所有的申请行为以及与咨询有关的其它事项进行审查。
迈德同信有权对用户的注册数据进行查阅，发现注册数据中存在任何问题或怀疑，均有权向用户发出询问及要求改正的通知或者直接作出删除等处理。
迈德同信有权根据不同情况选择保留或删除相关信息或继续、停止对该用户提供服务，并追究相关法律责任。
因在迈德同信上发生服务纠纷，引起诉讼的，用户通过司法部门或行政部门依照法定程序要求迈德同信提供相关数据，迈德同信应积极配合并提供有关资料。
用户对服务内容不满意，可以向迈德同信提出投诉，迈德同信有义务依据情况协调沟通。

系统因下列状况无法正常运作，使用户无法使用服务时，迈德同信不承担损害赔偿责任，该状况包括但不限于：
（1）迈德同信在教育平台公告之系统停机维护期间。
（2）电信设备出现故障不能进行数据传输的。
（3）因台风、地震、海啸、洪水、停电、战争、恐怖袭击等不可抗力之因素，造成系统障碍不能执行业务的。
（4）由于黑客攻击、电信部门技术调整或故障、银行方面的问题等原因而造成的服务中断或者延迟。

第4条责任限制

除非另有明确的书面说明，教育平台及其所包含的或以其它方式通过教育平台提供给您的全部信息、内容、材料、产品（包括软件）和服务，均是在“按现状”和“按现有”的基础上提供的。除非另有明确的书面说明，迈德同信不对教育平台的运营及其包含在教育平台上的信息、内容、材料、产品（包括软件）或服务作任何形式的、明示或默示的声明或担保（根据中华人民共和国法律另有规定的以外）。
迈德同信不担保教育平台所包含的或以其它方式通过教育平台提供给您的全部信息、内容、材料、产品（包括软件）和服务、其服务器或从教育平台发出的电子信件、信息没有病毒或其他有害成分。如因不可抗力或其它本站无法控制的原因使教育平台销售系统崩溃或无法正常使用导致网上交易无法完成或丢失有关的信息、记录等，迈德同信会合理地尽力协助处理善后事宜。
迈德同信所承载的内容（文、图、视频、音频）均为传播有益健康资讯目的，不对其真实性、科学性、严肃性做任何形式保证。
迈德同信所有信息仅供参考，不做个别诊断、用药和使用的根据。
迈德同信有限公司致力于提供正确、完整的健康资讯，但不保证信息的正确性和完整性，且不对因信息的不正确或遗漏导致的任何损失或损害承担责任。
迈德同信所提供的任何医疗信息，仅供参考，不能替代医生和其他医务人员的建议，如自行使用迈德同信中资料发生偏差，迈德同信概不负责，亦不负任何法律责任。
迈德同信保留对本声明作出不定时修改的权利。

第5条用户信息和隐私保护

5.1 注册用户应为医疗工作人员，自行诚信向教育平台提供注册资料，用户同意其提供的注册资料真实、准确、完整、合法有效，用户注册资料如有变动的，应及时更新。如果用户提供的注册资料不合法、不真实、不准确、不详尽的，用户需承担因此引起的相应责任及后果，迈德同信保留随时终止该用户使用教育平台各项服务的权利。
5.2 用户在教育平台进行购买活动时，涉及用户真实姓名/名称、通信地址、联系电话、电子邮箱等隐私信息的，教育平台将予以严格保密，除非得到用户的授权或法律另有规定，教育平台不会向外界披露用户隐私信息。
5.3 用户注册成功后，将产生用户名和密码等账户信息，您可以根据教育平台的规定更改您的密码。用户应谨慎合理的保存和使用其用户名和密码。用户若发现任何非法使用用户账号或存在安全漏洞的情况，请立即通知教育平台并向公安机关报案。
5.4 用户同意，迈德同信拥有通过邮件、短信电话等形式，向在教育平台注册、购买的用户发送订单信息、促销活动等告知信息的权利。
5.5 用户不得将在教育平台注册获得的账户借给他人使用，否则用户应承担由此产生的全部责任，并与实际使用人承担连带责任。
5.6 用户同意，迈德同信有权使用用户的注册信息、用户名、密码等信息，登陆进入用户的注册账户，进行证据保全，包括但不限于公证、见证等。

第6条用户义务

本协议依据国家相关法律法规规章制定，用户同意严格遵守以下义务：
（1）不得传输或发表：煽动抗拒、破坏宪法和法律、行政法规实施的言论，煽动颠覆国家政权，推翻社会主义制度的言论，煽动分裂国家、破坏国家统一的的言论，煽动民族仇恨、民族歧视、破坏民族团结的言论；
（2）从中国大陆向境外传输资料信息时必须符合中国有关法规；
（3）不得利用教育平台从事洗钱、窃取商业秘密、窃取个人信息等违法犯罪活动；
（4）不得干扰教育平台的正常运转，不得侵入教育平台及国家计算机信息系统；
（5）不得传输或发表任何违法犯罪的、骚扰性的、中伤他人的、辱骂性的、恐吓性的、伤害性的、庸俗的、淫秽的、不文明的等信息资料；
（6）不得传输或发表损害国家社会公共利益和涉及国家安全的信息资料或言论；
（7）不得教唆他人从事本条所禁止的行为；
（8）不得利用在教育平台注册的账户进行牟利性经营活动；
（9）不得发布任何侵犯他人著作权、商标权等知识产权或合法权利的内容；用户应关注并遵守教育平台不时公布或修改的各类合法规则规定。教育平台保有删除各类不符合法律政策或不真实的信息内容而无须通知用户的权利。若用户未遵守以上规定的，教育平台有权作出独立判断并采取暂停或关闭用户帐号等措施。用户须对自己在网上的言论和行为承担法律责任。

第7条知识产权声明

7.1 用户一旦接受本协议，即表明该用户主动将其在任何时间段在教育平台发表的任何形式的信息内容（包括但不限于客户评价、客户咨询、各类话题文章等信息内容）的财产性权利等任何可转让的权利，如著作权财产权（包括并不限于：复制权、发行权、出租权、展览权、表演权、放映权、广播权、信息网络传播权、摄制权、改编权、翻译权、汇编权以及应当由著作权人享有的其他可转让权利），全部独家且不可撤销地转让给迈德同信所有，用户同意迈德同信有权就任何主体侵权而单独提起诉讼。
7.2 本协议已经构成《中华人民共和国著作权法》第二十五条及相关法律规定的著作财产权等权利转让书面协议，其效力及于用户在教育平台上发布的任何受著作权法保护的作品内容，无论该等内容形成于本协议订立前还是本协议订立后。
7.3 用户同意并已充分了解本协议的条款，承诺不将已发表于教育平台的信息，以任何形式发布或授权其它主体以任何方式使用（包括但限于在各类网站、媒体上使用）。
7.4 迈德同信是教育平台的制作者，拥有此教育平台上全部内容及资源的著作权等合法权利，受国家法律保护，有权不时地对本协议及教育平台的内容进行修改，并在教育平台公示，无须另行通知用户。在法律允许的最大限度范围内，迈德同信对本协议及教育平台内容拥有解释权。
7.5 除法律另有强制性规定外，未经迈德同信明确的特别书面许可，任何单位或个人不得以任何方式非法地全部或部分复制、转载、引用、链接、抓取或以其他方式使用教育平台的信息内容，否则，迈德同信有权追究其法律责任。
7.6 教育平台所刊登的资料信息（诸如文字、图表、标识、按钮图标、图像、声音文件片段、数字下载、数据编辑和软件），均是迈德同信或其内容提供者的财产，受中国和国际版权法的保护。教育平台上所有内容的汇编是迈德同信的排他财产，受中国和国际版权法的保护。教育平台上所有软件都是迈德同信或其关联公司或其软件供应商的财产，受中国和国际版权法的保护。

第8条法律管辖和适用

本协议的订立、执行和解释及争议的解决均应适用在中华人民共和国大陆地区适用之有效法律（但不包括其冲突法规则）。如发生本协议与适用之法律相抵触时，则这些条款将完全按法律规定重新解释，而其它有效条款继续有效。如缔约方就本协议内容或其执行发生任何争议，双方应尽力友好协商解决；协商不成时，任何一方均可向迈德同信所在地的人民法院提起诉讼。

第9条其他

9.1 迈德同信教育平台所有者是指在政府部门依法许可或备案的迈德同信经营主体。
9.2 迈德同信尊重用户和消费者的合法权利，本协议及教育平台上发布的各类规则、声明等其他内容，均是为了更好的、更加便利的为用户提供服务。教育平台欢迎用户和社会各界提出意见和建议，迈德同信将虚心接受并适时修改本协议及教育平台的各类规则。
9.3 您点击本协议上方的“同意以下协议，提交”按钮即视为您完全接受本协议，在点击之前请您再次确认已知悉并完全理解本协议的全部内容。
            </textarea>
          </div>
          <input v-model="agree" id="agree" type="checkbox"><label for="agree">同意用户协议</label>

          <br>

          <p>
            <button type="submit" class="button expanded">报&emsp;名</button>
          </p>
          <p class="text-center"><a href="/home/login">已有账号?点击登录</a></p>
        </div>
      </form>

    </div>
  </div>
@endsection


@section('js')
  <script type="text/javascript" src="{{asset('vendor/area-select/jquery.area.js')}}"></script>
  <script>
    $(function () {
        $('#city-select').citys({
            required:false,
            nodata:'',
            onChange:function(data){
                var lists = {};
                if(data['direct']){
                    lists.province = data.province;
                    lists.city = data.province;
                    lists.area = data.city;
                }else {
                    lists.province = data.province;
                    lists.city = data.city;
                    lists.area = data.area;
                }
                console.log(lists);
                $('#save-province').val(lists.province);
                $('#save-city').val(lists.city);
                $('#save-area').val(lists.area);
            }
        });
    });

    vm = new Vue({
      el: '#sign_up',
      data: {
        phone: '',
        sms: '',
        password: '',
        password_confirmation: '',
        agree: true
      },
      methods: {
        get_auth_code: function () {

          $('#error_phone').addClass('hide');
          $('#error_phone').text('请输入正确的手机号!');
          $('.input-group-button button').attr("disabled", "disabled");

          var myreg = /^(((12[0-9]{1})|(13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
          if (myreg.test(vm.phone)) {

            var i = 61;
            timer();
            function timer() {
              i--;
              $('.input-group-button button').text(i + '秒后重发');
              if (i == 0) {
                clearTimeout(timer);
                $('.input-group-button button').removeAttr("disabled");
                $('.input-group-button button').text('重新发送');
              } else {
                setTimeout(timer, 1000);
              }
            }

            $.get('/home/register/sms', {phone: vm.phone}, function (data) {
                if (data.success) {
                } else {
                  $('#error_phone').text(data.error_message);
                  $('#error_phone').removeClass('hide');
                }
              }
            );
          } else {
            $('#error_phone').removeClass('hide');
            $('.input-group-button button').removeAttr("disabled");
          }
        },
        chooseOtherHosLevel:function(com) {
          selectOther(com.$event.target,'save_hospital_level');
        },
        chooseOtherOffice:function(com) {
          selectOther(com.$event.target,'save_office');
        },
        chooseOtherTitle:function(com) {
          selectOther(com.$event.target,'save_title');
        }
      },
      computed: {
        is_same: function () {
          if(this.phone === ''||this.sms === ''|| this.password === ''||this.password_confirmation === ''||this.agree === false||this.password != this.password_confirmation){
            $("button[type='submit']").attr("disabled", "disabled")
          }else{
            var myreg = /^(((12[0-9]{1})|(13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
            if (myreg.test(vm.phone)) {
              $('#error_phone').addClass('hide');
              $("button[type='submit']").removeAttr("disabled");
            }
            else {
              $('#error_phone').text('请输入正确的手机号!');
              $('#error_phone').removeClass('hide');
              $("button[type='submit']").attr("disabled", "disabled");
            }
          }
          return (this.password === this.password_confirmation)?false:true
        }
      }
    });

    function selectOther(target,idataName){
        var selectInd = target.selectedIndex;
          var len = target.length;
          var value = target.selectedOptions[0].value;
          var idataInput = $('input[name="' + idataName +'"]'); 
          if (selectInd==len-1){
            idataInput.val('');
            idataInput.show();
          } else{
            idataInput.hide();
            idataInput.val(value);
          }
    }
  </script>
@endsection