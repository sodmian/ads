# Hello, world!
Здрасьте-здрасьте, люди добрые! Итак, если мы еще предварительно не знакомы, то давайте знакомиться. Меня зовут Дмитрий, мне 32 года, занимаюсь веб-разработкой с 2016 года и раз уж вы здесь, то скорее всего Вы перешли по ссылке из моего резюме или я Вам скинул ссылку на данный проект в целях предварительной оценки своих компетенций (а все потому, что вы крутые).

Поэтому специально для вас я собрал данный микрофреймворк с нуля, а уже на базе него сделал простенькое приложение цель которого - выгрузка объявлений в импровизированную таблицу. Ладно, не буду отнимать ваше время и постараюсь вкратце изложить все технические нюансы данного проекта. 

# Нус, начнемс...
Для того, чтобы развернуть проект локально нам понадобится:
1.	PHP 8, MySQL, Composer, NPM
2.	Подтягиваем зависимости командой ***composer install***
3.	Тащим пакетами всю nodeятину через ***npm install***
4.	Теперь мы готовы к последнему этапу – собираем все в кучу командой ***npm run build*** и переходим в браузер
5.	Хотя, стоп. Про базу чуть не забыл) Тут не нужен дамп, так как присутствует всего лишь одна табличка, поэтому просто выполним код, указанный ниже:

	CREATE DATABASE IF NOT EXISTS `ads`;
	USE `ads`;
	
	CREATE TABLE IF NOT EXISTS `list_ads` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `date` datetime NOT NULL DEFAULT current_timestamp(),
	  `text` TEXT NOT NULL,
	  `contacts` TEXT NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Баги есть? А если найду?
![Все объявления](https://skr.sh/i/031021/cEQf3ja8.jpg?download=1&name=%D0%A1%D0%BA%D1%80%D0%B8%D0%BD%D1%88%D0%BE%D1%82%2003-10-2021%2022:13:03.jpg)

Да, не густо! Проект собирался в свободное время, 4 ночи подряд после 20 дневного дедлайна больше напоминающего лютый геноцид злым капиталистом угнетенных веб-разработчиков.

Мне не было интересно копировать уже существующие микрофреймворки, поэтому я решил изобрести именно свой велосипед, пусть с квадратными колесами, но в этом и суть. Я ведь не хочу обманывать вас и обманываться сам, что знаю все и обо всем...

Нет! Как раз цель - показать, что я понимаю, а чего нет. НО!!! Есть и нюансы. Данный проект не пример идеального микрофреймворка, на котором что-то там такое собрали, здесь присутствуют миксы из возможных реализаций. В нем нет чего-то правильного или не правильного, а есть демонстрация возможных решений, которые могут показаться не совсем удачными.

На этом скрине показаны 2 вкладки. Можете проверить, они, кстати говоря, адаптивные. Вся страница, по сути, представляет собой один основной компонент Vue, а вкладки выполняют роль роутов. Если мы кликнем по второй вкладке, то перед нами откроется форма ниже:

![Добавить новые](https://skr.sh/i/031021/tmwnEIJc.jpg?download=1&name=%D0%A1%D0%BA%D1%80%D0%B8%D0%BD%D1%88%D0%BE%D1%82%2003-10-2021%2022:13:16.jpg)

Вооот, уже интереснее! В этом компоненте присутствует поле для загрузки csv файла, который по задумке должен выгрузить список объявлений в базу. Но есть условия... Заголовок не должен содержать больше N символов (а сколько конкретно вы можете сами определить в .env файле. Кстати я его не переименовывал в .env-example ибо в этой ситуации оно и не нужно). Также присутствует передача csrf токена, превалидация, а также вывод ошибок. В принципе, большего текущий контекст и не требует.

![Успешная проверка](https://skr.sh/i/031021/orFDAZM3.jpg?download=1&name=%D0%A1%D0%BA%D1%80%D0%B8%D0%BD%D1%88%D0%BE%D1%82%2003-10-2021%2022:45:18.jpg)

Первое состояние - успешная проверка. Далее при клике по кнопке "Загрузить" прогрузится файл и сработает Emit на родительском компоненте формы, что вызовет перерисовку первой вкладки.

![Ничего, в следующий раз точно повезет!](https://skr.sh/i/031021/4ySuez0a.jpg?download=1&name=%D0%A1%D0%BA%D1%80%D0%B8%D0%BD%D1%88%D0%BE%D1%82%2003-10-2021%2022:46:15.jpg)

Второе состояние - файл не прошел предварительную проверку.

![Мы предупреждали...](https://skr.sh/i/031021/xtVD2psV.jpg?download=1&name=%D0%A1%D0%BA%D1%80%D0%B8%D0%BD%D1%88%D0%BE%D1%82%2003-10-2021%2022:47:14.jpg)

Третье состояние - мы проверили каждую строчку файла и вывели сообщение с указанием номера строки, в которой было обнаружено превышение лимита символов. Кстати, кнопка в правой части формы будет заблокирована до того момента пока файл не пройдет проверку.

Вот, собственно, и вся логика, но под эту логику я написал:

- Маленький фреймворк с применением шаблона MVP
- Используя для сборки стилей *Gulp*
- Однофайловые компоненты на *Vue*, *Vue Router* для имитации табов
- В качестве сборщика у нас выступает *Webpack*
- За автозагрузкой классов следит *Composer* со стандартом *PSR-4*
- Для вьюшек используется шаблонизатор *Twig*
- А еще в проекте присутствует практически готовый (на 99%) *REST API*

По всем правилам MVP вьюшка ничего не знает о модели, каждый метод максимально изолирован, не перегружен кодом и выполняет строго свою задачу, как и классы в целом. Роут, конечно, необходимо доработать и сделать возможность добавления sef-ссылок. Но это уже будет следующей целью...

# Цель должна быть конкретной, измеримой, достижимой
Немного о понимании задач, как довольно-таки важной теме. Для меня понимание задачи также важно, как и освоение практической составляющей при написании кода. За весь период своей работы я неоднократно убеждался в том, что правильно заданный вопрос - уже половина решения. Не только лишь один стек помогает решить задачу грамотно, но и понимание поставленной задачи, декомпозиция её и разбор деталей. К примеру, это помогает в переоценке недооцененных по срокам задач. Вот и, наверное, все, что хотелось написать. Жду обратной связи по коду :)
